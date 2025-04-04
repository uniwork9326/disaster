<?php
$weapons = [
    "TSAR BOMBA" => [
        "yield" => "50 Mt",
        "description" => "The Tsar Bomba was the most powerful nuclear weapon ever detonated, developed by the Soviet Union. Its explosion was equivalent to 50 million tons of TNT.
        
        Deep Dive:
Detonated: 1961 by the Soviet Union

Yield: ~50 megatons (originally designed for 100)

Type: Thermonuclear (Hydrogen Bomb)

Tsar Bomba was the biggest nuclear explosion ever recorded. The blast was so huge, it shattered windows nearly 900km away. The fireball itself was 8km wide, and the mushroom cloud reached the stratosphere.

If this bomb went off over a major city, it would completely erase it — and then some. The shockwave circled the Earth three times.


Radius Breakdown:
Inner Zone: Total vaporization. Nothing survives. Even bunkers would be turned to ash.

Middle Zone: Intense heat burns skin miles away, buildings collapse, lethal radiation. Most people die within minutes or hours.

Outer Zone: Radiation sickness spreads out far, injuries from debris, and long-term cancer risk. Survivable, but brutal.",
        "image" => "tsar_bomba.jpg",
        "diagram" => "tsar_bomba_diagram.jpg",
        "wikipedia" => "https://en.wikipedia.org/wiki/Tsar_Bomba"
    ],
    "CASTLE BRAVO" => [
        "yield" => "15 Mt",
        "description" => "Castle Bravo was the first dry fuel hydrogen bomb test by the United States, significantly larger than expected and causing extensive fallout.
        
         Deep Dive:
Detonated: 1954, Bikini Atoll (USA test)

Yield: ~15 megatons (expected 6, went way over)

Type: Thermonuclear (dry fuel hydrogen bomb)

Castle Bravo was America’s first dry-fuel H-bomb test — and it went horribly wrong. It was over twice as powerful as expected. The fallout reached inhabited islands, sickened civilians, and caused international outrage. A Japanese fishing crew nearby was caught in the fallout — some of them died.

Radius Breakdown:
Inner Zone: Gigantic fireball. Complete vaporization of island and test site.

Middle Zone: Deadly shockwave, 90% death chance. Everything is flattened or burned.

Outer Zone: Long-range radioactive fallout. Even people 100km away were hit with sickness. Major long-term health damage.",
        "image" => "castle_bravo.jpg",
        "diagram" => "castle_bravo_diagram.jpg",
        "wikipedia" => "https://en.wikipedia.org/wiki/Castle_Bravo"
    ],
    "IVY KING" => [
        "yield" => "500 kt",
        "description" => "Ivy King was a high-yield nuclear fission bomb tested by the United States in 1952, designed to avoid using thermonuclear fusion.
        Deep Dive:
Detonated: 1952, Enewetak Atoll

Yield: ~500 kilotons

Type: Pure fission (no fusion, biggest of its kind)

Ivy King was America’s flex — “How powerful can we go without using hydrogen fusion?” It was all fission, all plutonium, and it still released over 30 times the energy of the Hiroshima bomb. This makes it a perfect mid-tier bomb for your sim: not city-ending, but still absolutely devastating.

Radius Breakdown:
Inner Zone: Cratered ground, buildings erased. Anyone nearby = gone.

Middle Zone: Most structures collapse, high pressure and heat kill almost everyone.

Outer Zone: Serious injuries and radiation damage. Survivors would be traumatised and need immediate medical help.",
        "image" => "ivy_king.jpg",
        "diagram" => "ivy_king_diagram.jpg",
        "wikipedia" => "https://en.wikipedia.org/wiki/Ivy_King"
    ],
    "FAT MAN" => [
        "yield" => "20 kt",
        "description" => "Fat Man was a plutonium implosion-type nuclear bomb dropped on Nagasaki in 1945, ending World War II.
        Deep Dive:
Detonated: 1945 over Nagasaki, Japan

Yield: ~21 kilotons

Type: Plutonium-based implosion fission bomb

Fat Man was more advanced than Little Boy, using an implosion design that was way more efficient. It detonated in a valley, which contained the blast — but still killed tens of thousands instantly.

Radius Breakdown:
Inner Zone: Everything is demolished. The industrial district of Nagasaki was reduced to rubble.

Middle Zone: Concrete buildings collapse, people suffer lethal burns, radiation sickness hits within hours.

Outer Zone: Partial destruction, survivors injured or exposed to radiation. Less intense than Hiroshima due to terrain.",
        "image" => "fat_man.jpg",
        "diagram" => "fat_man_diagram.jpg",
        "wikipedia" => "https://en.wikipedia.org/wiki/Fat_Man"
    ],
    "LITTLE MAN" => [
        "yield" => "15 kt",
        "description" => "Little Boy was the uranium-based bomb dropped on Hiroshima in 1945, being the first nuclear weapon used in warfare.
        Deep Dive:
Detonated: 1945 over Hiroshima, Japan

Yield: ~15 kilotons

Type: Uranium-based fission bomb

This was the first nuclear weapon ever used in war. It was inefficient — only about 1.7% of the uranium actually reacted — but it still flattened Hiroshima and caused over 100,000 deaths. The design was simple but deadly.

Radius Breakdown:
Inner Zone: Fireball kills everyone instantly. Anything made of wood, paper, or flesh is gone.

Middle Zone: Most buildings collapse, people die from burns and falling structures.

Outer Zone: Flying glass, intense heat, radiation exposure. Some survive but suffer long-term effects.",
        "image" => "little_boy.jpg",
        "diagram" => "little_boy_diagram.jpg",
        "wikipedia" => "https://en.wikipedia.org/wiki/Little_Boy"
    ]
];

header("Content-Type: text/html");

echo "<style>
    body { font-family: Arial, sans-serif; background-color: #1e1e1e; color: white; text-align: center; }
    .weapon-container { display: flex; justify-content: center; gap: 20px; margin-top: 20px; }
    .weapon { background: #333; padding: 15px; border-radius: 10px; cursor: pointer; transition: 0.3s; }
    .weapon:hover { background: #555; }
    .weapon-info { margin-top: 30px; display: none; padding: 20px; background: #444; border-radius: 10px; }
    img { width: 300px; border-radius: 10px; }
    #proceed-btn { margin-top: 30px; padding: 15px 30px; font-size: 18px; background: #28a745; color: white; border: none; cursor: pointer; border-radius: 10px; }
    #proceed-btn:hover { background: #218838; }
</style>";

echo "<h1>Select a Weapon to View Details</h1>";

echo "<div class='weapon-container'>";
foreach ($weapons as $name => $info) {
    echo "<div class='weapon' onclick=\"showWeapon('$name')\">
            <h3>$name</h3>
            <p>Yield: {$info['yield']}</p>
            <a href='{$info['wikipedia']}' target='_blank' style='color: #00aaff;'>Wikipedia</a>
          </div>";
}
echo "</div>";

echo "<div id='weapon-info' class='weapon-info'>
        <h2 id='weapon-name'></h2>
        <p id='weapon-desc'></p>
        <img id='weapon-img' src='' alt='Weapon Image'>
        <h3>Diagram:</h3>
        <img id='weapon-diagram' src='' alt='Weapon Diagram'>
        <p><a id='weapon-wikipedia' href='' target='_blank' style='color: #00aaff;'>View on Wikipedia</a></p>
      </div>";

// Add the "Proceed" button
echo "<button id='proceed-btn' onclick=\"window.location.href='index.php'\">Return to Simulation</button>";

echo "<script>
    let weapons = " . json_encode($weapons) . ";
    function showWeapon(name) {
        let info = weapons[name];
        document.getElementById('weapon-name').innerText = name;
        document.getElementById('weapon-desc').innerText = info.description;
        document.getElementById('weapon-img').src = info.image;
        document.getElementById('weapon-diagram').src = info.diagram;
        document.getElementById('weapon-wikipedia').href = info.wikipedia;
        document.getElementById('weapon-info').style.display = 'block';
    }
</script>";
?>
