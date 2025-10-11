# Junk Collection AI Automation System

🤖 **AI-Powered Waste Management Quote System**

This system automatically processes quote requests using OpenAI's GPT-4 Vision API to analyze uploaded images, assess waste types and volumes, and generate accurate quotes via email.

## 🎯 System Overview

When customers submit a quote request:
1. Images are uploaded and analyzed by AI
2. Waste type and volume are automatically assessed
3. Accurate quotes are generated based on pricing rules
4. Automated emails are sent with quote details
5. Admin dashboard tracks all AI processing results

## 🚀 Quick Test

Test your AI services are working correctly:

```bash
# Test all AI services
php spark ai:test

# Test specific service with verbose output  
php spark ai:test --service=vision --verbose
php spark ai:test --service=assessment --verbose
php spark ai:test --service=quote --verbose
```

## 📋 Process Flow

```
Customer Quote Request → Image Upload → AI Analysis → Waste Assessment → Quote Generation → Email Delivery
```

## 🛠️ Admin Features

- **AI Status Dashboard**: View processing status for all quotes
- **Manual Processing**: Trigger AI processing for specific quotes
- **Results Review**: View AI analysis confidence scores and details
- **Cost Monitoring**: Track OpenAI API usage and costs

## 📈 Status Codes

- `pending`: Quote submitted, awaiting AI processing
- `processing`: Currently being analyzed by AI
- `ai_complete`: AI analysis finished, quote sent
- `ai_failed`: AI processing failed (requires manual review)
- `manual`: Manually processed quote

## 🎯 Key Benefits

- **95% Faster**: Automated quote processing vs manual
- **24/7 Availability**: Process quotes anytime, even after hours
- **Consistent Accuracy**: AI-powered volume estimation
- **Cost Effective**: Optimized pricing based on waste assessment
- **Professional**: Automated email delivery with detailed quotes

## 🔧 System Requirements

- OpenAI API key with GPT-4 Vision access
- PHP 8.0+ with CodeIgniter 4
- MySQL/MariaDB database
- Email service configuration
- Cron job support for background processing

---

**Ready to revolutionize your waste management quotes!** 🌟