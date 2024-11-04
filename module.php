<?php 
session_start();
ob_start();
include("connection.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learning Materials</title>
    <style>
        :root {
            --primary-color: #ed6346;
            --secondary-color: #ffe4ce;
            --text-color: #333;
            --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: var(--secondary-color);
            color: var(--text-color);
            line-height: 1.6;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .backbutton {
            width: 40px;
            height: 40px;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .backbutton:hover {
            transform: scale(1.1);
        }

        .page-title {
            color: var(--primary-color);
            font-size: 2rem;
            text-align: center;
            margin: 20px 0;
        }

        .module-section {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: var(--card-shadow);
        }

        .module-title {
            color: var(--primary-color);
            font-size: 1.5rem;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--primary-color);
        }

        .content-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .content-card {
            background: #f8f8f8;
            border-radius: 8px;
            padding: 15px;
            transition: transform 0.2s;
            cursor: pointer;
        }

        .content-card:hover {
            transform: translateY(-5px);
            background-color: #f0f0f0;
        }

        .content-thumbnail {
            width: 100%;
            height: 160px;
            background-color: #ddd;
            border-radius: 5px;
            margin-bottom: 10px;
            overflow: hidden;
        }

        .content-thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .content-title {
            font-size: 1.1rem;
            font-weight: bold;
            margin-bottom: 5px;
            color: var(--text-color);
        }

        .content-info {
            font-size: 0.9rem;
            color: #666;
        }

        .module-description {
            margin-bottom: 20px;
            color: #666;
        }

        .quiz-button {
            display: block;
            width: 200px;
            margin: 30px auto;
            padding: 12px 24px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 25px;
            font-size: 1.1rem;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            transition: transform 0.2s, background-color 0.2s;
        }

        .quiz-button:hover {
            transform: scale(1.05);
            background-color: #d94f32;
        }

        @media (max-width: 768px) {
            body {
                padding: 10px;
            }

            .content-grid {
                grid-template-columns: 1fr;
            }

            .module-section {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <a href="homepage.php">
                <img src="img/back.svg" alt="" class="backbutton">
            </a>
            <h1 class="page-title">Learning Materials</h1>
        </div>

        <div class="module-section">
            <div class="content-grid">
                <div class="content-card" onclick="openContent('<?php echo htmlspecialchars($content['content_url']); ?>')">
                    <div class="content-thumbnail">
                    </div>
                </div>
            </div>
        </div>
    </div>


</body>
</html>