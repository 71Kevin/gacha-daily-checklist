# gacha-daily-checklist

## Overview

gacha-daily-checklist is a simple web application built with pure PHP, HTML, and CSS. It is designed to help gacha game enthusiasts keep track of their daily, weekly, and monthly tasks. This application is particularly useful for players who need to manage multiple daily tasks in games like Fate/Grand Order and Genshin Impact.

## Features

- **Daily, Weekly, and Monthly Checklists**: Keep track of tasks that reset daily, weekly, or monthly.
- **Persistence**: Tasks that have been checked off are saved and displayed to the user on subsequent visits.
- **Task Reset**: Daily tasks reset every day, weekly tasks reset every Monday, and monthly tasks reset on the 1st of each month.
- **Logging**: Completed tasks are logged with timestamps for tracking.

## Example Checklist

The included `checklist.json` file is an example that includes tasks for Fate/Grand Order and Genshin Impact. Users can customize this file to suit their own needs.

## Usage

### Requirements

- Web server with PHP support
- Basic understanding of PHP and file permissions

### Installation

1. Clone the repository or download the source code.
2. Place the files in your web server's root directory.
3. Ensure that the web server has write permissions for the directory where the application is installed.

### Files

- `index.php`: Main file to handle the checklist display and submission.
- `styles.css`: CSS file for styling the checklist.
- `checklist.json`: JSON file containing the list of tasks.
- `status.json`: JSON file to store the status of submitted tasks.
- `checklist_log.txt`: Log file to keep track of completed tasks.

### Functionality

1. **Checklist Display**: The application reads tasks from `checklist.json` and displays them as checkboxes grouped by daily, weekly, and monthly categories.
2. **Task Submission**: Users can check off completed tasks and submit them. Submissions are limited to once per day for daily tasks, once per week for weekly tasks, and once per month for monthly tasks.
3. **Persistence and Logging**: Submitted tasks are saved in `status.json` and logged in `checklist_log.txt`.

### Example JSON Structure

```json
{
    "daily": {
        "Fate/Grand Order": [
            "Daily login",
            "FP summon",
            "Events/interludes (if any active)"
        ],
        "Genshin Impact": [
            "Daily check-in",
            "Commissions",
            "Expeditions",
            "Serenitea Pot",
            "Events (if any active)",
            "Plan your Resin / spend Resin",
            "Collect Materials / Do daily mobs to get materials (e.g., red medals)",
            "Forge Your Rocks (mining / forging stones at the blacksmith)",
            "Redeem purple keys / use purple keys"
        ]
    },
    "weekly": {
        "Fate/Grand Order": [
            "Weekly quests"
        ],
        "Genshin Impact": [
            "Weekly bosses (Arlecchino, Stormterror, Baleia Devoradora, Signora)",
            "Weekly reputation city missions"
        ]
    },
    "monthly": {
        "Fate/Grand Order": [
            "Chaldean Visionary Flames",
            "Print grails"
        ],
        "Genshin Impact": [
            "Paimon's shop"
        ]
    },
    "other": {
        "Genshin Impact": [
            "Abyss",
            "The Catch R5"
        ]
    }
}
```

## Screenshots

https://github.com/71Kevin/gacha-daily-checklist/assets/37316637/5ce04ee7-210f-489f-beb2-43ec971af0ae

## Contributing

Contributions are welcome! Please fork the repository and submit a pull request with your improvements.

## Acknowledgments

This application is inspired by the need for a simple tool to help gacha game players keep track of their numerous daily, weekly, and monthly tasks.

## License

This project is licensed under the ISC License.
