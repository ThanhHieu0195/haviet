function playPause() {
        var myVideo = $('#my_video_1');
        if (myVideo[0].paused) {
            myVideo[0].play();
            myVideo.parent().addClass('active');
        }
        else {
            myVideo[0].pause();
            myVideo.parent().removeClass('active');
        }
    } 