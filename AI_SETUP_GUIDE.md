# AI Quote Processing Setup Guide

## Automated Quote Processing System

This system automatically processes quote requests using AI services to:
- Analyze uploaded images for waste identification
- Assess waste types and volumes
- Generate accurate pricing estimates
- Send automated quotes to customers via email

## Installation & Setup

### 1. Environment Variables
Ensure these are set in your `.env` file:

```env
# OpenAI API Configuration
OPENAI_API_KEY=your_openai_api_key_here
OPENAI_API_URL=https://api.openai.com/v1/chat/completions
OPENAI_MODEL=gpt-4-vision-preview
OPENAI_MAX_TOKENS=2000
OPENAI_TEMPERATURE=0.3
```

### 2. Database Migration
Run the migration to add AI processing fields:
```bash
php spark migrate
```

### 3. Background Processing Setup

#### Option A: Cron Job (Recommended for Production)
Add this to your server's crontab:

```bash
# Process AI quotes every 5 minutes
*/5 * * * * cd /path/to/your/project && php spark ai:process-quotes --limit=10 >/dev/null 2>&1

# Process quotes every minute during business hours (8 AM - 8 PM)
* 8-20 * * * cd /path/to/your/project && php spark ai:process-quotes --limit=5 >/dev/null 2>&1
```

#### Option B: Windows Task Scheduler
For Windows servers, create a scheduled task:

1. Open Task Scheduler
2. Create Basic Task
3. Set trigger: Every 5 minutes
4. Action: Start a program
5. Program: `php`
6. Arguments: `spark ai:process-quotes --limit=10`
7. Start in: `D:\Projects\Nadheem\learn\junk-col`

### 4. Manual Processing Commands

```bash
# Process up to 10 pending quotes
php spark ai:process-quotes

# Process with custom limit
php spark ai:process-quotes --limit=5

# Force reprocess all quotes (including already processed)
php spark ai:process-quotes --force

# Process specific number of quotes
php spark ai:process-quotes --limit=20
```

## How It Works

### 1. Quote Submission Flow
1. Customer submits quote with images
2. Quote saved with status "pending"
3. AI processing triggered automatically (immediate or queued)
4. Status updates: `pending` → `ai_processing` → `ai_quoted`

### 2. AI Processing Pipeline
1. **Image Analysis**: AI analyzes uploaded images to identify waste types
2. **Waste Assessment**: Business logic applied to categorize and estimate volume
3. **Quote Generation**: Pricing calculated based on location, waste type, and regulations
4. **Email Notification**: Automated quote sent to customer
5. **Admin Notification**: Quote appears in admin panel with AI insights

### 3. Status Workflow
- `pending`: Initial submission
- `ai_queued`: Queued for AI processing
- `ai_processing`: Currently being processed by AI
- `ai_quoted`: AI processing complete, quote generated
- `ai_error`: AI processing failed
- `contacted`: Manual follow-up started
- `quoted`: Manual quote provided
- `accepted`/`rejected`: Customer response
- `completed`: Service completed

## Admin Panel Features

### Quote Management
- View AI-generated estimates
- See confidence scores
- Review waste analysis results
- Manual override capabilities
- Trigger AI reprocessing

### Dashboard Analytics
- AI processing statistics
- Success rates and confidence scores
- Processing time metrics
- Cost tracking

## Configuration Options

### AI Processing Timing
Edit `app/Controllers/Quote.php` - `shouldProcessImmediately()`:
- Immediate processing during business hours (8 AM - 8 PM)
- Queue for batch processing outside business hours

### Email Templates
Customize email content in `app/Libraries/AIQuoteProcessor.php` - `generateQuoteEmailContent()`:
- Quote breakdown formatting
- Company branding
- Call-to-action buttons

### Pricing Rules
Modify pricing logic in:
- `app/Services/QuoteGeneratorService.php` - Base pricing and fees
- `app/Services/WasteAssessmentService.php` - Volume calculations

## Monitoring & Troubleshooting

### Check Processing Status
```bash
# View recent AI processing logs
tail -f writable/logs/log-*.log | grep "AI Quote"

# Check quotes pending processing
php spark ai:process-quotes --limit=0  # Shows count without processing
```

### Common Issues

1. **OpenAI API Errors**: Check API key and quota
2. **Image Processing**: Ensure images are accessible and valid formats
3. **Email Sending**: Verify SMTP configuration
4. **File Permissions**: Check upload directory permissions

### Debug Mode
Set `ENVIRONMENT=development` in `.env` for:
- Immediate processing
- Detailed error logging
- Extended timeout limits

## Performance Optimization

### For High Volume
- Increase processing limits in cron jobs
- Add multiple processing workers
- Implement Redis queue (advanced)
- Scale OpenAI API requests

### Cost Management
- Monitor OpenAI token usage
- Implement request caching
- Set processing quotas
- Track per-quote processing costs

## Security Considerations

- Secure OpenAI API key storage
- Validate uploaded image types and sizes
- Sanitize AI responses before storage
- Log all processing activities
- Implement rate limiting for API calls

## Testing

### Manual Testing
1. Submit a quote with images via the website
2. Check admin panel for status updates
3. Monitor logs for processing details
4. Verify email delivery

### Automated Testing
```bash
# Test with a specific quote ID
php spark ai:process-quotes --force --limit=1
```

For production deployment, ensure all environment variables are properly set and monitoring is in place.