<div class="body-loading">
    <div class="container-loading">
        <div class="ring"></div>
        <div class="ring"></div>
        <div class="ring"></div>
        <span class="loading">Loading...</span>
    </div>
</div>

<style>
    *{
        padding: 0;
        margin: 0;
        box-sizing: border-box;
    }
    .body-loading{
        width: 100vw;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        background: rgba(0, 0, 0, 0.4)
    }
    .container-loading{
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .ring {
        width: 200px;
        height: 200px;
        border: 0px solid white;
        border-radius: 50%;
        position: absolute;
    }

    .ring:nth-child(1){
        border-bottom-width: 8px;
        border-color: rgb(255, 0, 255);
        animation: rotate 2s linear infinite;
    }

    .ring:nth-child(2){
        border-right-width: 8px;
        border-color: rgb(119, 249, 119);
        animation: rotate2 2s linear infinite;
    }

    .ring:nth-child(3){
        border-top-width: 8px;
        border-color: rgb(117, 176, 254);
        animation: rotate3 2s linear infinite;
    }

    .loading{
        color:  white;
    }

    @keyframes rotate{
        0%{
            transform: rotateX(35deg) rotateY(-45deg) rotateZ(0deg);
        }
        100%
        {
            transform: rotateX(35deg) rotateY(-45deg) rotateZ(360deg);
        }
    }

    @keyframes rotate2{
        0%{
            transform: rotateX(50deg) rotateY(10deg) rotateZ(0deg);
        }
        100%
        {
            transform: rotateX(50deg) rotateY(10deg) rotateZ(360deg);
        }
    }

    @keyframes rotate3{
        0%{
            transform: rotateX(35deg) rotateY(55deg) rotateZ(0deg);
        }
        100%
        {
            transform: rotateX(35deg) rotateY(55deg) rotateZ(360deg);
        }
    }
</style>
