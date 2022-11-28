<ul class="nav" ui-nav="">
    <li class="nav-header hidden-folded">
        <small class="text-muted">Main</small>
    </li>

    <li ui-sref-active="active">
        <a href="dashboard.html" ui-sref="app.dashboard">
        <span class="nav-icon">
          <i class="material-icons"><svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48">
	<path d="M24,20c-7.72,0-14,6.28-14,14h4c0-5.51,4.49-10,10-10s10,4.49,10,10h4C38,26.28,31.721,20,24,20z" fill="#0cc2aa"></path>
</svg></i>
        </span>
            <span class="nav-text">Dashboard</span>
        </a>
    </li>

    <li class="">
        <a>
        <span class="nav-caret">
          <i class="fa fa-caret-down"></i>
        </span>
            <span class="nav-label"><b class="label rounded label-sm warn">5</b></span>
            <span class="nav-icon">
          <i class="material-icons"><svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48">
	<rect x="20" y="32" width="8" height="8" fill="#0cc2aa"></rect>
	<rect x="8" y="20" width="8" height="8" fill="#0cc2aa"></rect>
	<rect x="20" y="8" width="8" height="8" fill="#0cc2aa"></rect>
	<rect x="32" y="20" width="8" height="8" fill="#0cc2aa"></rect>
</svg></i>
        </span>
            <span class="nav-text">Apps</span>
        </a>
        <ul class="nav-sub">
            <li ui-sref-active="active">
                <a href="inbox.html" ui-sref="app.inbox.list"><span class="nav-text">Inbox</span></a>
            </li>
            <li ui-sref-active="active">
                <a href="contact.html" ui-sref="app.contact"><span class="nav-text">Contacts</span></a>
            </li>
            <li ui-sref-active="active">
                <a href="calendar.html" ui-sref="app.calendar"><span class="nav-text">Calendar</span></a>
            </li>
            <li ui-sref-active="active" class="hide" ng-class="{'show': 1}">
                <a ui-sref="app.note.list"><span class="nav-text">Note</span></a>
            </li>
            <li ui-sref-active="active" class="hide" ng-class="{'show': 1}">
                <a ui-sref="app.todo"><span class="nav-text">Todo</span></a>
            </li>
        </ul>
    </li>

    <li ng-class="{active:$state.includes('app.layout')}">
        <a>
        <span class="nav-caret">
          <i class="fa fa-caret-down"></i>
        </span>
            <span class="nav-icon">
          <i class="material-icons"><svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48">
	<rect x="20" y="10" width="10" height="12" fill="#0cc2aa"></rect>
	<rect x="8" y="24" width="10" height="12" fill="#0cc2aa"></rect>
	<rect x="32" y="24" width="10" height="12" fill="#0cc2aa"></rect>
</svg></i>
        </span>
            <span class="nav-text">Layouts</span>
        </a>
        <ul class="nav-sub">
            <li ui-sref-active="active">
                <a href="headers.html" ui-sref="app.layout.header"><span class="nav-text">Header</span></a>
            </li>
            <li ui-sref-active="active">
                <a href="asides.html" ui-sref="app.layout.aside"><span class="nav-text">Aside</span></a>
            </li>
            <li ui-sref-active="active">
                <a href="footers.html" ui-sref="app.layout.footer"><span class="nav-text">Footer</span></a>
            </li>
        </ul>
    </li>

    <li ui-sref-active="active">
        <a href="widget.html" ui-sref="app.widget">
        <span class="nav-icon">
          <i class="material-icons"><svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48">
	<rect x="8" y="34" width="20" height="4" fill="#0cc2aa"></rect>
	<rect x="8" y="18" width="32" height="4" fill="#0cc2aa"></rect>
</svg></i>
        </span>
            <span class="nav-text">Widgets</span>
        </a>
    </li>

    <li class="nav-header hidden-folded">
        <small class="text-muted">Components</small>
    </li>

    <li ng-class="{active:$state.includes('app.ui')}">
        <a>
        <span class="nav-caret">
          <i class="fa fa-caret-down"></i>
        </span>
            <span class="nav-label"><b class="label label-sm accent">8</b></span>
            <span class="nav-icon">
          <i class="material-icons"><svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48">
	<rect x="6" y="34" width="12" height="4" fill="#0cc2aa"></rect>
	<rect x="6" y="10" width="20" height="4" fill="#0cc2aa"></rect>
	<rect x="22" y="22" width="20" height="4" fill="#0cc2aa"></rect>
</svg></i>
        </span>
            <span class="nav-text">UI kit</span>
        </a>
        <ul class="nav-sub nav-mega nav-mega-3">
            <li ui-sref-active="active" class="hide" ng-class="{'show': 1}">
                <a ui-sref="app.ui.angularstrap"><span class="nav-text">AngularStrap</span></a>
            </li>
            <li ui-sref-active="active" class="hide" ng-class="{'show': 1}">
                <a ui-sref="app.ui.bootstrap"><span class="nav-text">UI Bootstrap</span></a>
            </li>
            <li ui-sref-active="active">
                <a href="arrow.html" ui-sref="app.ui.arrow"><span class="nav-text">Arrow</span></a>
            </li>
            <li ui-sref-active="active">
                <a href="box.html" ui-sref="app.ui.box"><span class="nav-text">Box</span></a>
            </li>
            <li ui-sref-active="active">
                <a href="button.html" ui-sref="app.ui.button"><span class="nav-text">Button</span></a>
            </li>
            <li ui-sref-active="active">
                <a href="color.html" ui-sref="app.ui.color"><span class="nav-text">Color</span></a>
            </li>
            <li ui-sref-active="active">
                <a href="dropdown.html" ui-sref="app.ui.dropdown"><span class="nav-text">Dropdown</span></a>
            </li>
            <li ui-sref-active="active">
                <a href="grid.html" ui-sref="app.ui.grid"><span class="nav-text">Grid</span></a>
            </li>
            <li ui-sref-active="active">
                <a href="icon.html" ui-sref="app.ui.icon"><span class="nav-text">Icon</span></a>
            </li>
            <li ui-sref-active="active">
                <a href="label.html" ui-sref="app.ui.label"><span class="nav-text">Label</span></a>
            </li>
            <li ui-sref-active="active">
                <a href="list.html" ui-sref="app.ui.list"><span class="nav-text">List Group</span></a>
            </li>
            <li ui-sref-active="active">
                <a href="modal.html" ui-sref="app.ui.modal"><span class="nav-text">Modal</span></a>
            </li>
            <li ui-sref-active="active">
                <a href="nav.html" ui-sref="app.ui.nav"><span class="nav-text">Nav</span></a>
            </li>
            <li ui-sref-active="active">
                <a href="progress.html" ui-sref="app.ui.progress"><span class="nav-text">Progress</span></a>
            </li>
            <li ui-sref-active="active">
                <a href="social.html" ui-sref="app.ui.social"><span class="nav-text">Social</span></a>
            </li>
            <li ui-sref-active="active">
                <a href="sortable.html" ui-sref="app.ui.sortable"><span class="nav-text">Sortable</span></a>
            </li>
            <li ui-sref-active="active">
                <a href="streamline.html" ui-sref="app.ui.streamline"><span class="nav-text">Streamline</span></a>
            </li>
            <li ui-sref-active="active">
                <a href="timeline.html" ui-sref="app.ui.timeline"><span class="nav-text">Timeline</span></a>
            </li>
            <li ui-sref-active="active">
                <a href="map.vector.html" ui-sref="app.ui.vectormap"><span class="nav-text">Vector Map</span></a>
            </li>
            <li ui-sref-active="active" class="hide" ng-class="{'show': 1}">
                <a href="#/app/ui/googlemap" ui-sref="app.googlemap"><span class="nav-text">Google Map</span></a>
            </li>
        </ul>
    </li>

    <li ng-class="{active:$state.includes('app.page')}">
        <a>
        <span class="nav-caret">
          <i class="fa fa-caret-down"></i>
        </span>
            <span class="nav-label"><b class="label no-bg">9</b></span>
            <span class="nav-icon">
          <i class="material-icons"><svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48">
	<rect x="30" y="38" width="4" height="4" fill="#0cc2aa"></rect>
	<rect x="38" y="14" width="4" height="4" fill="#0cc2aa"></rect>
	<path d="M38,6v4h4C42,7.79,40.21,6,38,6z" fill="#0cc2aa"></path>
	<rect x="38" y="30" width="4" height="4" fill="#0cc2aa"></rect>
	<rect x="30" y="6" width="4" height="4" fill="#0cc2aa"></rect>
	<rect x="38" y="22" width="4" height="4" fill="#0cc2aa"></rect>
	<path d="M38,42c2.21,0,4-1.79,4-4h-4V42z" fill="#0cc2aa"></path>
</svg></i>
        </span>
            <span class="nav-text">Pages</span>
        </a>
        <ul class="nav-sub nav-mega">
            <li ui-sref-active="active">
                <a href="profile.html" ui-sref="app.page.profile">
                    <span class="nav-text">Profile</span>
                </a>
            </li>
            <li ui-sref-active="active">
                <a href="setting.html" ui-sref="app.page.setting">
                    <span class="nav-text">Setting</span>
                </a>
            </li>
            <li ui-sref-active="active">
                <a href="search.html" ui-sref="app.page.search">
                    <span class="nav-text">Search</span>
                </a>
            </li>
            <li ui-sref-active="active">
                <a href="faq.html" ui-sref="app.page.faq">
                    <span class="nav-text">FAQ</span>
                </a>
            </li>
            <li ui-sref-active="active">
                <a href="gallery.html" ui-sref="app.page.gallery">
                    <span class="nav-text">Gallery</span>
                </a>
            </li>
            <li ui-sref-active="active">
                <a href="invoice.html" ui-sref="app.page.invoice">
                    <span class="nav-text">Invoice</span>
                </a>
            </li>
            <li ui-sref-active="active">
                <a href="price.html" ui-sref="app.page.price">
                    <span class="nav-text">Price</span>
                </a>
            </li>
            <li ui-sref-active="active">
                <a href="blank.html" ui-sref="app.page.blank">
                    <span class="nav-text">Blank</span>
                </a>
            </li>
            <li>
                <a href="signin.html" ui-sref="access.signin">
                    <span class="nav-text">Sign In</span>
                </a>
            </li>
            <li>
                <a href="signup.html" ui-sref="access.signup">
                    <span class="nav-text">Sign Up</span>
                </a>
            </li>
            <li>
                <a href="forgot-password.html" ui-sref="access.forgot-password">
                    <span class="nav-text">Forgot Password</span>
                </a>
            </li>
            <li>
                <a href="lockme.html" ui-sref="access.lockme">
                    <span class="nav-text">Lockme Screen</span>
                </a>
            </li>
            <li>
                <a href="404.html" ui-sref="404">
                    <span class="nav-text">Error 404</span>
                </a>
            </li>
            <li>
                <a href="505.html" ui-sref="505">
                    <span class="nav-text">Error 505</span>
                </a>
            </li>
        </ul>
    </li>

    <li ng-class="{active:$state.includes('app.form')}">
        <a>
        <span class="nav-caret">
          <i class="fa fa-caret-down"></i>
        </span>
            <span class="nav-icon">
          <i class="material-icons"><svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48">
	<circle cx="24" cy="24" r="6" fill="#0cc2aa"></circle>
</svg></i>
        </span>
            <span class="nav-text">Form</span>
        </a>
        <ul class="nav-sub">
            <li ui-sref-active="active">
                <a href="form.layout.html" ui-sref="app.form.layout"><span class="nav-text">Form Layout</span></a>
            </li>
            <li ui-sref-active="active">
                <a href="form.element.html" ui-sref="app.form.element"><span class="nav-text">Form Element</span></a>
            </li>
            <li ui-sref-active="active">
                <a href="form.validation.html" ui-sref="app.form.validation"><span class="nav-text">Form Validation</span></a>
            </li>
            <li ui-sref-active="active">
                <a href="form.select.html" ui-sref="app.form.select"><span class="nav-text">Select</span></a>
            </li>
            <li ui-sref-active="active">
                <a href="form.editor.html" ui-sref="app.form.editor"><span class="nav-text">Editor</span></a>
            </li>
            <li ng-class="{'hide': 1}">
                <a href="form.picker.html"><span class="nav-text">Picker</span></a>
            </li>
            <li ng-class="{'hide': 1}">
                <a href="form.wizard.html"><span class="nav-text">Wizard</span></a>
            </li>
            <li ui-sref-active="active" class="hide" ng-class="{'show': 1}">
                <a ui-sref="app.form.slider"><span class="nav-text">Slider</span></a>
            </li>
            <li ui-sref-active="active" class="hide" ng-class="{'show': 1}">
                <a ui-sref="app.form.tree"><span class="nav-text">Tree</span></a>
            </li>
            <li ui-sref-active="active">
                <a href="form.dropzone.html" ui-sref="app.form.file-upload" class="no-ajax"><span class="nav-text">File Upload</span></a>
            </li>
            <li ui-sref-active="active" class="hide" ng-class="{'show': 1}">
                <a ui-sref="app.form.image-crop"><span class="nav-text">Image Crop</span></a>
            </li>
            <li ui-sref-active="active" class="hide" ng-class="{'show': 1}">
                <a ui-sref="app.form.editable"><span class="nav-text">Editable</span></a>
            </li>
        </ul>
    </li>

    <li ng-class="{active:$state.includes('app.table')}">
        <a>
        <span class="nav-caret">
          <i class="fa fa-caret-down"></i>
        </span>
            <span class="nav-icon">
          <i class="material-icons"><svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48">
	<rect x="6" y="30" width="4" height="4" fill="#0cc2aa"></rect>
	<rect x="6" y="14" width="4" height="4" fill="#0cc2aa"></rect>
	<rect x="14" y="22" width="28" height="4" fill="#0cc2aa"></rect>
</svg></i>
        </span>
            <span class="nav-text">Tables</span>
        </a>
        <ul class="nav-sub">
            <li ui-sref-active="active">
                <a href="static.html" ui-sref="app.table.static"><span class="nav-text">Static table</span></a>
            </li>
            <li ui-sref-active="active">
                <a href="datatable.html" ui-sref="app.table.datatable"><span class="nav-text">Datatable</span></a>
            </li>
            <li ui-sref-active="active">
                <a href="footable.html" ui-sref="app.table.footable"><span class="nav-text">Footable</span></a>
            </li>
            <li ui-sref-active="active" class="hide" ng-class="{'show': 1}">
                <a ui-sref="app.table.smart"><span class="nav-text">Smart table</span></a>
            </li>
            <li ui-sref-active="active" class="hide" ng-class="{'show': 1}">
                <a ui-sref="app.table.uigrid"><span class="nav-text">UI Grid</span></a>
            </li>
            <li ui-sref-active="active" class="hide" ng-class="{'show': 1}">
                <a ui-sref="app.table.editable"><span class="nav-text">Editable</span></a>
            </li>
        </ul>
    </li>
    <li ui-sref-active="active">
        <a>
        <span class="nav-caret">
          <i class="fa fa-caret-down"></i>
        </span>
            <span class="nav-label hidden-folded"><b class="label label-sm info">N</b></span>
            <span class="nav-icon">
          <i class="material-icons"><svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48">
	<rect x="14" y="12" width="4" height="24" fill="#0cc2aa"></rect>
	<rect x="30" y="12" width="4" height="24" fill="#0cc2aa"></rect>
</svg></i>
        </span>
            <span class="nav-text">Charts</span>
        </a>
        <ul class="nav-sub">
            <li ui-sref-active="active">
                <a href="chart.html" ui-sref="app.chart"><span class="nav-text">Chart</span></a>
            </li>
            <li ui-sref-active="active">
                <a>
            <span class="nav-caret">
              <i class="fa fa-caret-down"></i>
            </span>
                    <span class="nav-text">Echarts</span>
                </a>
                <ul class="nav-sub">
                    <li ui-sref-active="active">
                        <a href="echarts-line.html" ui-sref="app.echarts.line">
                            <span class="nav-text">line</span>
                        </a>
                    </li>
                    <li ui-sref-active="active">
                        <a href="echarts-bar.html" ui-sref="app.echarts.bar">
                            <span class="nav-text">Bar</span>
                        </a>
                    </li>
                    <li ui-sref-active="active">
                        <a href="echarts-pie.html" ui-sref="app.echarts.pie">
                            <span class="nav-text">Pie</span>
                        </a>
                    </li>
                    <li ui-sref-active="active">
                        <a href="echarts-scatter.html" ui-sref="app.echarts.scatter">
                            <span class="nav-text">Scatter</span>
                        </a>
                    </li>
                    <li ui-sref-active="active">
                        <a href="echarts-radar-chord.html" ui-sref="app.echarts.rc">
                            <span class="nav-text">Radar &amp; Chord</span>
                        </a>
                    </li>
                    <li ui-sref-active="active">
                        <a href="echarts-gauge-funnel.html" ui-sref="app.echarts.gf">
                            <span class="nav-text">Gauges &amp; Funnel</span>
                        </a>
                    </li>
                    <li ui-sref-active="active">
                        <a href="echarts-map.html" ui-sref="app.echarts.map">
                            <span class="nav-text">Map</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </li>

    <li class="nav-header hidden-folded">
        <small class="text-muted">Help</small>
    </li>

    <li class="hidden-folded" ui-sref-active="active">
        <a href="docs.html" ui-sref="app.docs">
            <span class="nav-text">Documents</span>
        </a>
    </li>

</ul>
