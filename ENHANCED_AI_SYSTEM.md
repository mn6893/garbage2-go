# AI Background Processing & Email Retry System - Enhanced

## 🔒 Duplicate Prevention System

### Background Processing Protection
- **Processing Lock**: Prevents multiple AI processes from running on the same quote simultaneously
- **Status Checks**: Verifies quotes haven't already been processed before starting
- **Time Limits**: Automatically clears stale processing locks after 30 minutes
- **Database Fields**:
  - `processing_lock`: Stores unique lock with timestamp
  - `ai_processing_started_at`: Tracks when processing began
  - `ai_processed_at`: Records completion time

### How It Works
```bash
# Background command automatically checks for duplicates
php spark ai:process-quotes --limit=10

# Force processing (bypasses duplicate checks)
php spark ai:process-quotes --force
```

## 📧 Email Delivery Tracking & Retry System

### Enhanced Email Status Tracking
- **Customer Email Status**: Tracks success/failure of quote emails to customers
- **Admin Email Status**: Monitors admin notification delivery
- **Retry Attempts**: Counts number of retry attempts (max 3 by default)
- **Error Logging**: Stores specific error messages for troubleshooting
- **Last Attempt Timestamp**: Records when emails were last attempted

### Database Fields Added
```sql
-- Email delivery tracking
email_sent_to_customer BOOLEAN DEFAULT FALSE
email_sent_to_admin BOOLEAN DEFAULT FALSE
customer_email_attempts INT DEFAULT 0
admin_email_attempts INT DEFAULT 0
last_email_attempt DATETIME NULL
customer_email_error TEXT NULL
admin_email_error TEXT NULL
```

### Email Retry Commands

#### Individual Quote Email Retry
```bash
# Retry both customer and admin emails for specific quote
php spark email:retry-failed --quote-id=123

# Retry only customer emails
php spark email:retry-failed --quote-id=123 --type=customer

# Retry only admin emails  
php spark email:retry-failed --quote-id=123 --type=admin
```

#### Bulk Email Retry
```bash
# Retry all failed emails (up to 50 quotes)
php spark email:retry-failed --limit=50 --type=both --max-attempts=3

# Retry only customer emails for quotes with less than 5 attempts
php spark email:retry-failed --type=customer --max-attempts=5 --limit=100
```

### Admin Dashboard Features

#### Email Status Overview
- **Customer Emails Sent**: Count of successful customer email deliveries
- **Customer Failed**: Number of failed customer email attempts
- **Admin Emails Sent**: Count of successful admin notifications
- **Admin Failed**: Number of failed admin email attempts
- **Need Retry**: Quotes with failed emails that can still be retried

#### Bulk Retry Interface
- **One-Click Retry**: Button to start bulk retry process for failed emails
- **Type Selection**: Choose to retry customer, admin, or both email types
- **Attempt Limits**: Configure maximum retry attempts before giving up
- **Background Processing**: Retries run in background without blocking interface

#### Individual Quote Management
- **Email Status Display**: Shows success/failure status for each email type
- **Error Messages**: Displays specific error details when emails fail
- **Retry Buttons**: Individual retry options for customer and admin emails
- **Attempt Counters**: Shows number of retry attempts made

## 🚀 Automated Background Processing

### Cron Job Setup (Recommended)
```bash
# Process AI quotes every 5 minutes
*/5 * * * * /usr/bin/php /path/to/project/spark ai:process-quotes --limit=20

# Retry failed emails every 30 minutes
*/30 * * * * /usr/bin/php /path/to/project/spark email:retry-failed --limit=30

# Daily cleanup (optional - remove old processing locks)
0 2 * * * /usr/bin/php /path/to/project/spark ai:process-quotes --force --limit=0
```

### Windows Task Scheduler
```powershell
# PowerShell command for Windows
php D:\path\to\project\spark ai:process-quotes --limit=20
php D:\path\to\project\spark email:retry-failed --limit=30
```

## 🔧 Configuration Options

### Email Retry Settings
- **Max Attempts**: Default 3, configurable up to 10
- **Retry Delay**: 2-second delay between email attempts
- **Batch Size**: Process up to 50 quotes per retry command
- **Timeout**: 30-minute timeout for processing locks

### AI Processing Settings
- **Concurrency Protection**: Only one AI process per quote at a time
- **Lock Expiration**: Processing locks expire after 30 minutes
- **Status Workflow**: `pending` → `ai_processing` → `ai_quoted` or `ai_error`

## 📊 Monitoring & Analytics

### Admin Dashboard Metrics
- Real-time email delivery statistics
- Processing status overview
- Failed email identification
- Retry success rates

### Log Files
- **Email Errors**: Detailed in CodeIgniter logs
- **AI Processing**: Success/failure tracking
- **Retry Attempts**: Individual attempt logging

## 🔄 Recovery Procedures

### When Emails Fail
1. **Check Admin Dashboard**: Review email status section
2. **View Error Details**: Check specific error messages in quote details
3. **Manual Retry**: Use individual retry buttons for immediate retry
4. **Bulk Recovery**: Use bulk retry for multiple failed emails

### When AI Processing Stalls
1. **Check Processing Locks**: Look for stale `processing_lock` entries
2. **Force Processing**: Use `--force` flag to bypass locks
3. **Manual Trigger**: Process specific quotes via admin interface

## 🎯 Best Practices

### Email Delivery
- Monitor failed email counts daily
- Set up email service monitoring
- Configure backup email providers
- Test email templates regularly

### AI Processing
- Run background commands every 5-10 minutes
- Monitor API usage and costs
- Keep image uploads optimized
- Maintain adequate server resources

### System Maintenance
- Regular database cleanup of old locks
- Monitor log file sizes
- Update API keys as needed
- Test backup and recovery procedures

---

**Your AI-powered quote system now includes enterprise-grade reliability with comprehensive duplicate prevention and email retry capabilities!** 🌟