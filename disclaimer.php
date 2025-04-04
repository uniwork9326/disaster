<?php
header("Content-Type: text/html");

echo "<style>
    body { 
        font-family: Arial, sans-serif; 
        background-color: #1e1e1e; 
        color: white; 
        display: flex; 
        justify-content: center; 
        align-items: center; 
        height: 100vh; 
        margin: 0; 
        flex-direction: column;
        text-align: center;
    }
    h1 { 
        font-size: 32px; 
        margin: 10px 0;
    }
    h2 { 
        font-size: 20px; 
        margin: 10px 0;
    }
    .weapon-container { 
        display: flex; 
        justify-content: center; 
        gap: 20px; 
        margin-top: 20px; 
    }
    .weapon { 
        background: #333; 
        padding: 15px; 
        border-radius: 10px; 
        cursor: pointer; 
        transition: 0.3s; 
    }
    .weapon:hover { 
        background: #555; 
    }
    .weapon-info { 
        margin-top: 30px; 
        display: none; 
        padding: 20px; 
        background: #444; 
        border-radius: 10px; 
    }
    img { 
        width: 300px; 
        border-radius: 10px; 
    }
    #proceed-btn { 
        margin-top: 30px; 
        padding: 15px 30px; 
        font-size: 18px; 
        background: #28a745; 
        color: white; 
        border: none; 
        cursor: pointer; 
        border-radius: 10px; 
    }
    #proceed-btn:hover { 
        background: #218838; 
    }
    #nuke-img { 
        margin-top: 20px; 
        width: 250px; 
        border-radius: 10px;
    }
</style>";

echo "<h1><u>WARNING!</u></h1>";
echo "<h2>This tool is for raising awareness regarding the destructive power of nuclear bombs.</h2>";
echo "<h2>Results produced by our simulator should <u>NEVER</u> be taken as fact, we hold no liability for misuse.</h2>";

// Add the "Proceed" button
echo "<button id='proceed-btn' onclick=\"window.location.href='index.php'\">I understand.</button>";

// Add the nuke image
echo "<img id='nuke-img' src='nuke.png' alt='Nuclear Bomb Image'>";
?>
