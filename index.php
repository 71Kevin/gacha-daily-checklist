<?php
session_start();
date_default_timezone_set('America/Sao_Paulo');

class Checklist {
    private $tasks;
    private $logFile = 'checklist_log.txt';
    private $dataFile = 'checklist.json';
    private $statusFile = 'status.json';

    public function __construct() {
        $this->loadTasks();
        $this->loadStatus();
        $this->handleSubmission();
        $this->resetTasksIfNeeded();
    }

    private function loadTasks() {
        if (file_exists($this->dataFile)) {
            $this->tasks = json_decode(file_get_contents($this->dataFile), true);
        } else {
            $this->tasks = [];
        }
    }

    private function loadStatus() {
        if (file_exists($this->statusFile)) {
            $_SESSION['submitted'] = json_decode(file_get_contents($this->statusFile), true);
        } else {
            $_SESSION['submitted'] = [];
        }
    }

    private function handleSubmission() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tasks'])) {
            $completedTasks = $_POST['tasks'];
            $timestamp = date('Y-m-d H:i:s');

            foreach ($completedTasks as $period => $tasks) {
                if (isset($_SESSION['submitted'][$period]) && $_SESSION['submitted'][$period]['date'] === date('Y-m-d')) {
                    echo '<p style="color: red;">You have already submitted for ' . $period . ' tasks today.</p>';
                    return;
                }

                if ($period == 'weekly' && isset($_SESSION['submitted'][$period]) && $_SESSION['submitted'][$period]['week'] == date('W')) {
                    echo '<p style="color: red;">You have already submitted for ' . $period . ' tasks this week.</p>';
                    return;
                }

                if ($period == 'monthly' && isset($_SESSION['submitted'][$period]) && $_SESSION['submitted'][$period]['month'] == date('m')) {
                    echo '<p style="color: red;">You have already submitted for ' . $period . ' tasks this month.</p>';
                    return;
                }
            }

            $this->logCompletedTasks($completedTasks, $timestamp);
            foreach ($completedTasks as $period => $tasks) {
                $_SESSION['submitted'][$period] = [
                    'date' => date('Y-m-d'),
                    'tasks' => $tasks,
                    'timestamp' => $timestamp
                ];

                if ($period == 'weekly') {
                    $_SESSION['submitted'][$period]['week'] = date('W');
                }

                if ($period == 'monthly') {
                    $_SESSION['submitted'][$period]['month'] = date('m');
                }
            }

            file_put_contents($this->statusFile, json_encode($_SESSION['submitted']));
        }
    }

    private function logCompletedTasks($completedTasks, $timestamp) {
        $log = "Completed tasks on $timestamp:\n";
        foreach ($completedTasks as $period => $periodTasks) {
            $log .= strtoupper($period) . ":\n";
            foreach ($periodTasks as $category => $tasks) {
                $log .= strtoupper($category) . ":\n";
                foreach ($tasks as $task) {
                    $log .= "- $task\n";
                }
            }
        }
        $log .= "\n";
        file_put_contents($this->logFile, $log, FILE_APPEND);
    }

    private function resetTasksIfNeeded() {
        $lastResetFile = 'last_reset.txt';
        $now = new DateTime();
        $lastReset = file_exists($lastResetFile) ? new DateTime(file_get_contents($lastResetFile)) : null;

        $reset = false;
        if (!$lastReset || $now->format('Y-m-d') !== $lastReset->format('Y-m-d')) {
            unset($_SESSION['submitted']['daily']);
            unset($_SESSION['submitted']['other']);
            $reset = true;
        }

        if (!$lastReset || $now->format('W') !== $lastReset->format('W')) {
            unset($_SESSION['submitted']['weekly']);
            $reset = true;
        }

        if (!$lastReset || $now->format('m') !== $lastReset->format('m')) {
            unset($_SESSION['submitted']['monthly']);
            $reset = true;
        }

        if ($reset) {
            file_put_contents($lastResetFile, $now->format('Y-m-d H:i:s'));
        }
    }

    public function displayTasks() {
        echo '<form method="post" action="">';

        $periods = ['daily', 'weekly', 'monthly', 'other'];
        foreach ($periods as $period) {
            if (isset($_SESSION['submitted'][$period]) && $_SESSION['submitted'][$period]['date'] === date('Y-m-d')) {
                echo '<p style="color: red;">You have already submitted for ' . $period . ' tasks today.</p>';
                echo '<p>Submitted tasks on ' . $_SESSION['submitted'][$period]['timestamp'] . ':</p>';
                echo '<ul>';
                foreach ($_SESSION['submitted'][$period]['tasks'] as $category => $tasks) {
                    echo "<li><strong>" . ucfirst($category) . ":</strong>";
                    echo '<ul>';
                    foreach ($tasks as $task) {
                        echo "<li>$task</li>";
                    }
                    echo '</ul></li>';
                }
                echo '</ul>';
                continue;
            }

            if ($period == 'weekly' && isset($_SESSION['submitted'][$period]) && $_SESSION['submitted'][$period]['week'] == date('W')) {
                echo '<p style="color: red;">You have already submitted for ' . $period . ' tasks this week.</p>';
                echo '<p>Submitted tasks on ' . $_SESSION['submitted'][$period]['timestamp'] . ':</p>';
                echo '<ul>';
                foreach ($_SESSION['submitted'][$period]['tasks'] as $category => $tasks) {
                    echo "<li><strong>" . ucfirst($category) . ":</strong>";
                    echo '<ul>';
                    foreach ($tasks as $task) {
                        echo "<li>$task</li>";
                    }
                    echo '</ul></li>';
                }
                echo '</ul>';
                continue;
            }

            if ($period == 'monthly' && isset($_SESSION['submitted'][$period]) && $_SESSION['submitted'][$period]['month'] == date('m')) {
                echo '<p style="color: red;">You have already submitted for ' . $period . ' tasks this month.</p>';
                echo '<p>Submitted tasks on ' . $_SESSION['submitted'][$period]['timestamp'] . ':</p>';
                echo '<ul>';
                foreach ($_SESSION['submitted'][$period]['tasks'] as $category => $tasks) {
                    echo "<li><strong>" . ucfirst($category) . ":</strong>";
                    echo '<ul>';
                    foreach ($tasks as $task) {
                        echo "<li>$task</li>";
                    }
                    echo '</ul></li>';
                }
                echo '</ul>';
                continue;
            }

            echo '<h2>' . ucfirst($period) . ' Tasks</h2>';
            foreach ($this->tasks[$period] as $category => $tasks) {
                echo "<h3>" . ucfirst($category) . "</h3><ul>";
                foreach ($tasks as $task) {
                    echo "<li><label><input type='checkbox' name='tasks[$period][$category][]' value='$task' style='transform: scale(1.5); margin-right: 10px;'> $task</label></li>";
                }
                echo "</ul>";
            }
        }

        echo '<button type="submit">Submit</button>';
        echo '</form>';
    }
}

$checklist = new Checklist();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Checklist</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Daily Checklist</h1>
        <?php $checklist->displayTasks(); ?>
    </div>
</body>
</html>
