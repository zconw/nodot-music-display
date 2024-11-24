<?php

$musicData = json_decode(file_get_contents('music_data.json'), true);


$randomIndex = array_rand($musicData);
$currentMusic = $musicData[$randomIndex];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>音乐播放页面</title>
    <style id="style">

body {
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    height: 100vh;
    max-width: 100%;
}

#music-player {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;

    background-image: url('<?php echo $currentMusic['cover_image'];?>');
    background-size: cover;
    background-repeat: no-repeat; 
    background-position: center;
    
    
    background-color: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(100px);
    height: 100vh;
}


#displayb {
     flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    background-color: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(100px);
    width: 100%;
}


button {
    margin: 5px;
    background-color: #ecf5ff00;
    cursor: pointer;
    border: solid #ffffff00 3px;
    font-size: 32px;
}


#music-cover {
    width: 350px;
    height: 350px;
    margin-bottom: 10px;
    border-radius: 20px;
    box-shadow: 0px 20px 50px 5px #d8d8d882;
    border: solid #ffffff 5px;
}


input[type="range"] {
    width: 80%;
    margin-top: 10px;
}

#music-list-popup {
    display: none;
    top: 10px;
    right: 15px;
    background-color: #ffffff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgb(172 172 172 / 20%);
    z-index: 999;
}

#music-list-popup ul {
    list-style-type: none;
    padding: 0;
    margin: 0;
}

#music-list-popup ul li {
    padding: 10px;
    border-bottom: 1px solid #ccc;
}


#overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 998;
}
        
        
input[type="range"] {
    -webkit-appearance: none;
    appearance: none;
    width: 98%;
    height: 5px;
    background-color: #f6f6f67d;
    outline: none;
    cursor: pointer;
    border-radius: 5px;
    margin-bottom: 18px;
}



input[type="range"]::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 0; 
    height: 100%;
    background-color: #007BFF;
    z-index: -1;
    transition: width 0.2s ease-in-out;
}


input[type="range"]::after {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 0;
    height: 100%;
    background-color: #28a745;
    z-index: -2;
    transition: width 0.2s ease-in-out;
}

input[type="range"]::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 15px;
    height: 15px;
    background-color: #fff;
    border-radius: 50%;
    cursor: pointer;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.3); 
}

#audio-player{
    width: 0px;
    height: 0px;
}

.jdt {
    position: fixed;
    bottom: 0px;
    width: 100%;
    height: 80px;
    background-color: rgb(255 255 255 / 65%);
    display: flex;
    flex-wrap: nowrap;
    flex-direction: column;
    align-items: center;
    justify-content: flex-end;
}

.bfj {
    display: flex;
    bottom: 11px;
    position: sticky;
}


.bfgd {
    position: fixed;
    bottom: 0px;
    height: px;
    display: flex;
    flex-wrap: nowrap;
    right: 15px;
}

h2 {
    font-size: 36px;
    line-height: 44px;
    height: 44px;
    margin-bottom: 6px;
    font-weight: bold;
    text-align: left;
}


    </style>
</head>

<body>
    <!-- 音乐播放器区域 -->
    <div id="music-player">
        
<div id="displayb">
        <img id="music-cover" src="<?php echo $currentMusic['cover_image'];?>" alt="<?php echo $currentMusic['name'];?>的封面">
        <h2><?php echo $currentMusic['name'];?></h2>
        <h3><?php echo $currentMusic['author'];?></h3>
        <audio id="audio-player" src="<?php echo $currentMusic['file_path'];?>" controls></audio>
        
        
        
        
        <div class="jdt">
        <input type="range" id="progress-bar" value="0" max="100" step="1">
        
        <div class="bfj">
        <button id="prev-button">⏮</button>
        <button id="play-pause-button">▶</button></button>
        <button id="next-button">⏭</button>
        
        
                <div class="bfgd">
        <button id="show-music-list-button">⩸</button>
        </div>
        
        
        </div>
        </div>
        
        
        
        
</div>
    </div>


<div id="music-list-popup">
    <h3>歌单列表</h3>
    <ul>
        <?php foreach ($musicData as $music) :?>
            <li data-file-path="<?php echo $music['file_path'];?>" data-cover-image="<?php echo $music['cover_image'];?>" data-name="<?php echo $music['name'];?>" data-author="<?php echo $music['author'];?>">
                <?php echo $music['name'];?> - <?php echo $music['author'];?>
            </li>
        <?php endforeach;?>
    </ul>
</div>


    <div id="overlay"></div>

    <script>

        const audioPlayer = document.getElementById('audio-player');
        const playPauseButton = document.getElementById('play-pause-button');
        const prevButton = document.getElementById('prev-button');
        const nextButton = document.getElementById('next-button');
        const progressBar = document.getElementById('progress-bar');
        const showMusicListButton = document.getElementById('show-music-list-button');
        const musicListPopup = document.getElementById('music-list-popup');
        const overlay = document.getElementById('overlay');

        let currentIndex = <?php echo $randomIndex;?>;


        function togglePlay() {
            if (audioPlayer.paused) {
                audioPlayer.play();
                playPauseButton.textContent = '⏸';
            } else {
                audioPlayer.pause();
                playPauseButton.textContent = '▶';
            }
        }

        function playPrev() {
            currentIndex--;
            if (currentIndex < 0) {
                currentIndex = <?php echo count($musicData) - 1;?>;
            }
            updateMusic();
        }


        function playNext() {
            currentIndex++;
            if (currentIndex >= <?php echo count($musicData);?>) {
                currentIndex = 0;
            }
            updateMusic();
        }

        function updateMusic() {
            const newMusic = <?php echo json_encode($musicData);?>[currentIndex];
            audioPlayer.src = newMusic['file_path'];
            document.getElementById('music-cover').src = newMusic['cover_image'];
            document.getElementById('music-cover').alt = newMusic['name'] + '的封面';
            document.getElementById('audio-player').load();
            document.getElementById('audio-player').play();
            document.getElementById('play-pause-button').textContent = '⏸';
            document.getElementById('progress-bar').value = 0;
            document.getElementsByTagName('h2')[0].textContent = newMusic['name'];
            document.getElementsByTagName('h3')[0].textContent = newMusic['author'];
            
            
            
                        const musicPlayer = document.getElementById('music-player');
            musicPlayer.style.backgroundImage = 'url(' + newMusic['cover_image'] + ')';
            musicPlayer.style.backgroundSize = 'cover';
            musicPlayer.style.backgroundRepeat = 'no-repeat';
            musicPlayer.style.backgroundPosition = 'center';
        }


        audioPlayer.addEventListener('timeupdate', function () {
            const progress = (audioPlayer.currentTime / audioPlayer.duration) * 100;
            progressBar.value = progress;
        });

        progressBar.addEventListener('input', function () {
            const newTime = (progressBar.value / 100) * audioPlayer.duration;
            audioPlayer.currentTime = newTime;
        });


        const musicListItems = musicListPopup.querySelectorAll('li');
        musicListItems.forEach(function (item, index) {
            item.addEventListener('click', function () {
                currentIndex = index;
                updateMusic();
            });
        });

        playPauseButton.addEventListener('click', togglePlay);
        prevButton.addEventListener('click', playPrev);
        nextButton.addEventListener('click', playNext);


        function showMusicList() {
            musicListPopup.style.display = 'block';
            overlay.style.display = 'block';
        }

        function hideMusicList() {
            musicListPopup.style.display = 'none';
            overlay.style.display = 'none';
        }

        showMusicListButton.addEventListener('click', showMusicList);

        overlay.addEventListener('click', hideMusicList);
    </script>
</body>

</html>