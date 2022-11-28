<div id="switcher">
    <div class="switcher box-color dark-white text-color" id="sw-theme">
        <a href="" ui-toggle-class="active" target="#sw-theme" class="box-color dark-white text-color sw-btn">
            <i class="fa fa-gear"></i>
        </a>
        <div class="box-header">
            <h2>Theme Switcher</h2>
        </div>
        <div class="box-divider"></div>
        <div class="box-body">
            <p id="settingLayout" class="hidden-md-down">
                <label class="md-check m-y-xs">
                    <input type="checkbox" ng-model="app.setting.folded">
                    <i class="green"></i>
                    <span class="hidden-folded">Folded Aside</span>
                </label>
                <label class="md-check m-y-xs">
                    <input type="checkbox" ng-model="app.setting.boxed">
                    <i class="green"></i>
                    <span class="hidden-folded">Boxed Layout</span>
                </label>
                <label class="m-y-xs pointer" ui-fullscreen="" id="full-screen-button">
                    <span class="fa fa-expand fa-fw m-r-xs"></span>
                    <span>Fullscreen Mode</span>
                </label>
            </p>
            <p>Colors:</p>
            <p id="settingColor">
                <label class="radio radio-inline m-a-0 ui-check ui-check-color ui-check-md" ng-click="setTheme({theme:{primary:'primary', accent:'accent', warn:'warn'}});">
                    <input type="radio" name="color" ng-model="app.setting.themeID" value="1">
                    <i class="primary"></i>
                </label>
                <label class="radio radio-inline m-a-0 ui-check ui-check-color ui-check-md" ng-click="setTheme({theme:{primary:'accent', accent:'cyan', warn:'warn'}})">
                    <input type="radio" name="color" ng-model="app.setting.themeID" value="2">
                    <i class="accent"></i>
                </label>
                <label class="radio radio-inline m-a-0 ui-check ui-check-color ui-check-md" ng-click="setTheme({theme:{primary:'warn', accent:'light-blue', warn:'warning'}})">
                    <input type="radio" name="color" ng-model="app.setting.themeID" value="3">
                    <i class="warn"></i>
                </label>
                <label class="radio radio-inline m-a-0 ui-check ui-check-color ui-check-md" ng-click="setTheme({theme:{primary:'success', accent:'teal', warn:'lime'}})">
                    <input type="radio" name="color" ng-model="app.setting.themeID" value="4">
                    <i class="success"></i>
                </label>
                <label class="radio radio-inline m-a-0 ui-check ui-check-color ui-check-md" ng-click="setTheme({theme:{primary:'info', accent:'light-blue', warn:'success'}})">
                    <input type="radio" name="color" ng-model="app.setting.themeID" value="5">
                    <i class="info"></i>
                </label>
                <label class="radio radio-inline m-a-0 ui-check ui-check-color ui-check-md" ng-click="setTheme({theme:{primary:'blue', accent:'indigo', warn:'primary'}})">
                    <input type="radio" name="color" ng-model="app.setting.themeID" value="6">
                    <i class="blue"></i>
                </label>
                <label class="radio radio-inline m-a-0 ui-check ui-check-color ui-check-md" ng-click="setTheme({theme:{primary:'warning', accent:'grey-100', warn:'success'}})">
                    <input type="radio" name="color" ng-model="app.setting.themeID" value="7">
                    <i class="warning"></i>
                </label>
                <label class="radio radio-inline m-a-0 ui-check ui-check-color ui-check-md" ng-click="setTheme({theme:{primary:'danger', accent:'grey-100', warn:'grey-300'}})">
                    <input type="radio" name="color" ng-model="app.setting.themeID" value="8">
                    <i class="danger"></i>
                </label>
            </p>
            <p>Themes:</p>
            <div class="text-u-c text-center _600 clearfix">
                <a href="" class="p-a col-xs-6 light" ng-click="app.setting.bg=''">Light</a>
                <a href="" class="p-a col-xs-6 grey" ng-click="app.setting.bg='grey'"><span class="text-white">Grey</span></a>
                <a href="" class="p-a col-xs-6 dark" ng-click="app.setting.bg='dark'"><span class="text-white">Dark</span></a>
                <a href="" class="p-a col-xs-6 black" ng-click="app.setting.bg='black'"><span class="text-white">Black</span></a>
            </div>
        </div>
    </div>

</div>
