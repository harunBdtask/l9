<style>
    #loader, #modal-loader {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(226, 226, 226, 0.75) no-repeat center center;
        width: 100%;
        z-index: 1000;
    }

    .spin-loader {
        position: relative;
        top: 46%;
        left: 5%;
    }
</style>

<div id="loader">
    <div class="text-center spin-loader"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></div>
</div>