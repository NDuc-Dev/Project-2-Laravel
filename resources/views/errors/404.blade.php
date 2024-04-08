<script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">

<body>
    <div id="particles-js">
        <div class="page-404">
            <div class="outer">
                <div class="middle">
                    <div class="inner">
                        <!--BEGIN CONTENT-->
                        <div class="inner-circle">
                            <i class="fa fa-home"></i>
                            <span>404</span>
                        </div>
                        <span class="inner-status">Oops! You're lost</span>
                        <span class="inner-detail" style="color:#fff;">
                            We can not find the page you're looking for.
                            <a href="{{ route('home')}}" class="btn btn-info mtl">
                                <i class="fa fa-home"></i>&nbsp;
                                Return home
                            </a>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<style>
    .clearfix:before,
    .clearfix:after {
        display: table;

        content: ' ';
    }

    .clearfix:after {
        clear: both;
    }

    body {
        box-sizing: border-box;
        padding: 0;
        margin: 0;
        font-family: "Work Sans", -apple-system, BlinkMacSystemFont,
            "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif,
            "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
        background: #f0f0f0 !important;
    }

    #particles-js {
        width: 100%;
        height: 100%;
        background-color: #e53935;
    }

    .page-404 .outer {
        position: absolute;
        top: 0;

        display: table;

        width: 100%;
        height: 100%;
    }

    .page-404 .outer .middle {
        display: table-cell;

        vertical-align: middle;
    }

    .page-404 .outer .middle .inner {
        width: 300px;
        margin-right: auto;
        margin-left: auto;
    }

    .page-404 .outer .middle .inner .inner-circle {
        height: 300px;

        border-radius: 50%;
        background-color: #ffffff;
    }

    .page-404 .outer .middle .inner .inner-circle:hover i {
        color: #39bbdb !important;
        background-color: #f5f5f5;
        box-shadow: 0 0 0 15px #39bbdb;
    }

    .page-404 .outer .middle .inner .inner-circle:hover span {
        color: #39bbdb;
    }

    .page-404 .outer .middle .inner .inner-circle i {
        font-size: 5em;
        line-height: 1.5em;

        float: right;

        width: 1.4em;
        height: 1.4em;
        margin-top: -.7em;
        margin-right: -.5em;
        padding: 20px;

        -webkit-transition: all .4s;
        transition: all .4s;
        text-align: center;

        color: #f5f5f5 !important;
        border-radius: 50%;
        background-color: #39bbdb;
        box-shadow: 0 0 0 15px #f0f0f0;
    }

    .page-404 .outer .middle .inner .inner-circle span {
        font-size: 8em;
        font-weight: 700;
        line-height: 0.8em;

        display: block;

        -webkit-transition: all .4s;
        transition: all .4s;
        text-align: center;

        color: #e0e0e0;
    }

    .page-404 .outer .middle .inner .inner-status {
        font-size: 20px;

        display: block;

        margin-top: 20px;
        margin-bottom: 5px;

        text-align: center;

        color: #39bbdb;
    }

    .page-404 .outer .middle .inner .inner-detail {
        line-height: 1.4em;

        display: block;

        margin-bottom: 10px;

        text-align: center;

        color: #999999;
    }

    .page-404 .outer .middle .inner .inner-detail a{
        text-decoration: none !important;
        color: #39bbdb;
    }
</style>

<script>
    particlesJS("particles-js", {
        "particles": {
            "number": {
                "value": 80,
                "density": {
                    "enable": true,
                    "value_area": 800
                }
            },
            "color": {
                "value": "#ffffff"
            },
            "shape": {
                "type": "circle",
                "stroke": {
                    "width": 0,
                    "color": "#000000"
                },
                "polygon": {
                    "nb_sides": 5
                },
                "image": {
                    "src": "img/github.svg",
                    "width": 100,
                    "height": 100
                }
            },
            "opacity": {
                "value": 0.5,
                "random": false,
                "anim": {
                    "enable": false,
                    "speed": 1,
                    "opacity_min": 0.1,
                    "sync": false
                }
            },
            "size": {
                "value": 3,
                "random": true,
                "anim": {
                    "enable": false,
                    "speed": 40,
                    "size_min": 0.1,
                    "sync": false
                }
            },
            "line_linked": {
                "enable": true,
                "distance": 150,
                "color": "#ffffff",
                "opacity": 0.4,
                "width": 1
            },
            "move": {
                "enable": true,
                "speed": 6,
                "direction": "none",
                "random": false,
                "straight": false,
                "out_mode": "out",
                "bounce": false,
                "attract": {
                    "enable": false,
                    "rotateX": 600,
                    "rotateY": 1200
                }
            }
        },
        "interactivity": {
            "detect_on": "canvas",
            "events": {
                "onhover": {
                    "enable": true,
                    "mode": "grab"
                },
                "onclick": {
                    "enable": true,
                    "mode": "push"
                },
                "resize": true
            },
            "modes": {
                "grab": {
                    "distance": 140,
                    "line_linked": {
                        "opacity": 1
                    }
                },
                "bubble": {
                    "distance": 400,
                    "size": 40,
                    "duration": 2,
                    "opacity": 8,
                    "speed": 3
                },
                "repulse": {
                    "distance": 200,
                    "duration": 0.4
                },
                "push": {
                    "particles_nb": 4
                },
                "remove": {
                    "particles_nb": 2
                }
            }
        },
        "retina_detect": true
    });
</script>
