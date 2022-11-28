@if(getRole() == 'super-admin' || getRole() == 'admin')
	@includeIf('skeleton::partials.super_admin_nav')
@else
	<ul class="nav" ui-nav>
		@php $permissions = Session::get('permission_details') ?? []; @endphp
		@foreach($permissions as $moduleKey => $module)
			{{--get the value of every module and check active for main li--}}
			@php
				$activeArray = [];
						foreach ($module as $menuKey => $menu){
								if (strpos($menu['menu_url'],'/') !== false){
										array_push($activeArray, strstr($menu['menu_url'],'/',true));
								} else {
										array_push($activeArray,$menu['menu_url']);
								}
						foreach ($menu['submodule_menu'] as  $submenu){
								if (strpos($menu['menu_url'],'/') !== false){
										array_push($activeArray,$submenu->submenu_url);
								} else {
										array_push($activeArray,$submenu->submenu_url);
								}
						}
				}
			@endphp
			{{--logic implementation in main li--}}
			<li ui-sref-active="active" class={{  in_array(request()->segment(1), $activeArray) ? "active" : "" }}>
				<a>
                <span class="nav-caret">
                    <i class="fa fa-caret-down"></i>
                </span>
					<span class="nav-icon">
                    <i class="fa fa-plus-square"></i>
                </span>
					<span class="nav-text">{{ $moduleKey }}</span>
				</a>
				<ul class="nav-sub">
					@foreach($module as $menuKey => $menu)
						@php
							$arraySubActive = [];
							foreach ($menu['submodule_menu'] as  $submenu){
									if(strpos($menu['menu_url'],'/') !== false){
									array_push($arraySubActive,$submenu->submenu_url);
							} else {
									array_push($arraySubActive,$submenu->submenu_url);
							}
					}
						@endphp
						@if(count($menu['submodule_menu']) == 0)
							{{-- logic implementation in individual li --}}
							<li class={{setDynamicSingleActiveClass($menu['menu_url'])}}>
								<a href="{{ URL::to($menu['menu_url']) }}">
                        <span class="nav-icon">
                            <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                        </span>
									<span class="nav-text">{{ $menu['menu_name'] }} </span>
								</a>
							</li>
						@else
							{{--li for subActive menu--}}
							<li class="{{ in_array(request()->segment(1),$arraySubActive) ? "active" : "" }}">
								<a>
                        <span class="nav-caret">
                            <i class="fa fa-caret-down"></i>
                        </span>
									<span class="nav-icon">
                            <i class="fa fa-plus-square"></i>
                        </span>
									<span class="nav-text">{{ $menu['menu_name'] }}</span>
								</a>
								<ul class="nav-sub">
									@foreach($menu['submodule_menu'] as $submenu)
										<li class={{setDynamicSingleActiveClass($submenu->submenu_url)}}>
											<a href="{{ url($submenu->submenu_url) }}">
                                    <span class="nav-icon">
                                        <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                                    </span>
												<span class="nav-text">{{ $submenu->submenu_name }}</span>
											</a>
										</li>
									@endforeach
								</ul>
							</li>
						@endif
					@endforeach
				</ul>
			</li>
		@endforeach
	</ul>
@endif
