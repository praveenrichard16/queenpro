# Complete Lead Management Guide

## Overview
The Lead Management system provides comprehensive CRM functionality including lead tracking, scoring, followups, analytics, import/export, and more.

## Features

### Core Features
- **Lead Creation & Management**: Create, update, and manage leads
- **Lead Assignment**: Assign leads to staff members
- **Lead Sources**: Track where leads come from
- **Lead Stages**: Track lead progression through sales pipeline
- **Lead Scoring**: Automatic scoring based on multiple factors

### Advanced Features
- **Followups**: Schedule and track followup activities
- **Analytics**: Comprehensive lead analytics and reporting
- **Trash Management**: Soft delete with restore capability
- **Import/Export**: Bulk import/export leads via CSV/Excel
- **Activity Log**: Track all lead activities
- **Reminders**: Automated reminders for followups via Email/SMS/WhatsApp

## Lead Sources

Lead sources help you track where your leads are coming from.

### Managing Lead Sources
1. Navigate to **Admin Panel → Lead Management → Lead Sources**
2. **Add Lead Source**: Create new sources (e.g., Website, Referral, Social Media)
3. **Manage Lead Sources**: View, edit, or delete existing sources

### Common Lead Sources
- Website/Contact Form
- Referral
- Social Media
- Email Campaign
- Event/Trade Show
- Phone Inquiry
- Walk-in

## Lead Stages

Lead stages represent the progression of a lead through your sales pipeline.

### Managing Lead Stages
1. Navigate to **Admin Panel → Lead Management → Lead Stages**
2. **Add Lead Stage**: Create stages (e.g., New, Contacted, Qualified, Proposal, Won, Lost)
3. **Manage Lead Stages**: View, edit, or delete stages

### Typical Sales Pipeline
1. **New** - Just received lead
2. **Contacted** - Initial contact made
3. **Qualified** - Lead meets criteria
4. **Proposal** - Proposal sent
5. **Negotiation** - Discussing terms
6. **Won** - Lead converted to customer
7. **Lost** - Lead did not convert

## Lead Scoring

Lead scoring automatically calculates a score (0-100) based on:
- **Stage**: Higher stages = higher score
- **Expected Value**: Higher value = higher score
- **Source**: Some sources weighted higher
- **Activity**: Recent activity increases score
- **Followups**: Upcoming followups boost score

### Viewing Lead Scores
- Lead scores are displayed in the lead list
- Scores are color-coded (high = green, medium = yellow, low = red)
- Scores update automatically when lead changes

### Manual Score Recalculation
- Click **Recalculate Score** on any lead
- Or use API: `POST /api/v1/leads/{id}/recalculate-score`

## Followups

Followups help you track scheduled activities with leads.

### Creating Followups
1. Open a lead in **Manage Leads**
2. Click **Tools** to expand followup section
3. Click **Schedule Followup**
4. Fill in:
   - **Followup Date**: When to follow up
   - **Followup Time**: Specific time (optional)
   - **Notes**: What to discuss
   - **Status**: Scheduled, Completed, Cancelled
   - **Outcome**: Result of followup (optional)

### Followup Views
- **Today's Followups**: All followups scheduled for today
- **Next Followups**: Upcoming followups in the next 7 days

### Followup Reminders
- Automated reminders sent via Email/SMS/WhatsApp
- Reminders sent 1 hour before scheduled time
- Configure reminder channels in settings

## Lead Analytics

Comprehensive analytics help you understand your lead performance.

### Analytics Dashboard
Navigate to **Admin Panel → Lead Management → Lead Analytics**

### Metrics Available
- **Total Leads**: Total number of leads
- **Leads by Stage**: Distribution across stages
- **Leads by Source**: Performance by source
- **Conversion Rate**: Percentage of leads converted
- **Average Lead Score**: Average score across all leads
- **Trends**: Lead creation trends over time

### Charts
- **Funnel Chart**: Visual representation of leads by stage
- **Source Performance**: Bar chart showing leads by source
- **Trend Line**: Line chart showing lead creation over time

## Trash Management

Soft delete allows you to recover accidentally deleted leads.

### Deleting Leads
1. Click **Delete** on any lead
2. Lead is moved to trash (soft deleted)
3. Lead can be restored later

### Managing Trash
1. Navigate to **Lead Management → Trash Leads**
2. View all deleted leads
3. **Restore**: Restore a deleted lead
4. **Permanent Delete**: Permanently remove a lead (cannot be recovered)

## Import/Export

Bulk operations for managing large numbers of leads.

### Exporting Leads
1. Navigate to **Lead Management → Import/Export**
2. Apply filters (optional):
   - Search term
   - Stage
   - Source
3. Select format: CSV or Excel
4. Click **Export Leads**
5. Download the file

### Importing Leads
1. Prepare CSV/Excel file with columns:
   - name (required)
   - email (required)
   - phone (optional)
   - lead_source (name of source)
   - lead_stage (name of stage)
   - expected_value (optional)
   - notes (optional)
   - assigned_email (email of staff member)
   - lead_score (optional)
   - next_followup_date (optional)
   - next_followup_time (optional)

2. Navigate to **Lead Management → Import/Export**
3. Select import mode:
   - **Create**: Only create new leads
   - **Update**: Update existing leads (match by email)
   - **Replace**: Delete all leads and import new ones
4. Upload file
5. Review import results

## Activity Log

Track all activities related to a lead.

### Viewing Activity Log
1. Open a lead in **Manage Leads**
2. Click **Tools** to expand
3. View **Activity Log** section
4. See all activities:
   - Lead created
   - Stage changed
   - Assigned to user
   - Notes added
   - Followups scheduled/completed

### Adding Activity Notes
1. In Activity Log section, click **Add Note**
2. Enter note description
3. Save

## Phone Number Formatting

The system automatically handles phone number formatting:
- **Default Country Code**: +91 (India) - configurable
- **Automatic Addition**: Country code added automatically if missing
- **Normalization**: Phone numbers normalized to standard format

### Configuring Default Country Code
1. Navigate to **Admin Panel → Settings → General**
2. Set **Default Country Code** (e.g., 91 for India)
3. Save settings

## API Access

All lead management features are accessible via REST API:

### Basic Operations
- `GET /api/v1/leads` - List leads
- `POST /api/v1/leads` - Create lead
- `PUT /api/v1/leads/{id}` - Update lead
- `DELETE /api/v1/leads/{id}` - Delete lead

### Advanced Operations
- `GET /api/v1/leads/analytics/overview` - Get analytics
- `POST /api/v1/leads/{id}/recalculate-score` - Recalculate score
- `GET /api/v1/leads/trash` - List deleted leads
- `POST /api/v1/leads/{id}/restore` - Restore lead
- `GET /api/v1/leads/export` - Export leads
- `POST /api/v1/leads/import` - Import leads

### Followups
- `GET /api/v1/leads/{lead}/followups` - List followups
- `POST /api/v1/leads/{lead}/followups` - Create followup
- `PUT /api/v1/leads/{lead}/followups/{followup}` - Update followup
- `POST /api/v1/leads/{lead}/followups/{followup}/complete` - Complete followup

See API documentation for complete details.

## Best Practices

1. **Consistent Naming**: Use consistent naming for sources and stages
2. **Regular Followups**: Schedule followups promptly
3. **Update Stages**: Keep lead stages current
4. **Add Notes**: Document important interactions
5. **Monitor Scores**: Review low-scoring leads regularly
6. **Clean Data**: Regularly review and clean up old leads
7. **Use Analytics**: Review analytics to improve processes

## Troubleshooting

### Leads Not Appearing
- Check filters applied
- Verify user permissions
- Check if lead is in trash

### Followups Not Sending
- Verify reminder service is configured
- Check scheduled task is running
- Review application logs

### Import Errors
- Verify CSV format matches template
- Check required fields are present
- Ensure source/stage names match existing values

