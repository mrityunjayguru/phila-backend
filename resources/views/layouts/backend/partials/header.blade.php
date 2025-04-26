		<div class="app-header header-shadow">
			<div class="app-header__logo">
				<a class="logo-hdr" href="{{ url('/') }}" target="_blank"><img src="@if(Settings::get('logo')){{ asset(Settings::get('logo')) }} @endif"></a>
				<div class="header__pane ml-auto">
					<div>
						<button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar"><span class="hamburger-box"><span class="hamburger-inner"></span></span></button>
					</div>
				</div>
			</div>
			<div class="app-header__mobile-menu">
				<div>
					<button type="button" class="hamburger hamburger--elastic mobile-toggle-nav"> <span class="hamburger-box"><span class="hamburger-inner"></span></span></button>
				</div>
			</div>
			<div class="app-header__menu">
				<span>
					<button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
						<span class="btn-icon-wrapper"><i class="fa fa-ellipsis-v fa-w-6"></i></span>
					</button>
				</span>
			</div>
			<div class="app-header__content">
				<div class="app-header-left">
					<!--<div class="search-wrapper">
						<div class="input-holder">
							<input type="text" class="search-input" placeholder="Type to search">
							<button class="search-icon"><span></span></button>
						</div>
						<button class="close"></button>
					</div>-->
				</div>
				<div class="app-header-right">
					<div class="header-dots">
						<div class="dropdown">
							<!--<button type="button" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown" class="p-0 mr-2 btn btn-link">
								<span class="icon-wrapper icon-wrapper-alt rounded-circle">
									<span class="icon-wrapper-bg bg-danger"></span>
									<i class="icon text-danger icon-anim-pulse ion-android-notifications"></i>
									<span class="badge badge-dot badge-dot-sm badge-danger">Notifications</span>
								</span>
							</button>
							<div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-xl rm-pointers dropdown-menu dropdown-menu-right">
								<div class="dropdown-menu-header mb-0">
									<div class="dropdown-menu-header-inner bg-deep-blue">
										<div class="menu-header-image opacity-1" style="background-image: url('assets/images/dropdown-header/city3.jpg');"></div>
										<div class="menu-header-content text-dark">
											<h5 class="menu-header-title">Notifications</h5>
										</div>
									</div>
								</div>
								<div class="tab-content">
									<div class="tab-pane active" id="tab-messages-header" role="tabpanel">
										<div class="scroll-area-sm">
											<div class="scrollbar-container">
												<div class="p-3">
													<div class="notifications-box">
														<div class="vertical-time-simple vertical-without-time vertical-timeline vertical-timeline--one-column">
															<div class="vertical-timeline-item dot-danger vertical-timeline-element">
																<div><span class="vertical-timeline-element-icon bounce-in"></span>
																	<div class="vertical-timeline-element-content bounce-in">
																		<h4 class="timeline-title">All Hands Meeting</h4>
																		<span class="vertical-timeline-element-date"></span>
																	</div>
																</div>
															</div>
															<div class="vertical-timeline-item dot-warning vertical-timeline-element">
																<div> <span class="vertical-timeline-element-icon bounce-in"></span>
																	<div class="vertical-timeline-element-content bounce-in">
																		<p>Yet another one, at <span class="text-success">15:00 PM</span>
																		</p> <span class="vertical-timeline-element-date"></span>
																	</div>
																</div>
															</div>
															<div class="vertical-timeline-item dot-success vertical-timeline-element">
																<div><span class="vertical-timeline-element-icon bounce-in"></span>
																	<div class="vertical-timeline-element-content bounce-in">
																		<h4 class="timeline-title">Build the production release
                                                                            <span class="badge badge-danger ml-2">NEW</span>
                                                                        </h4>
																		<span class="vertical-timeline-element-date"></span>
																	</div>
																</div>
															</div>
															<div class="vertical-timeline-item dot-primary vertical-timeline-element">
																<div> <span class="vertical-timeline-element-icon bounce-in"></span>
																	<div class="vertical-timeline-element-content bounce-in">
																		<h4 class="timeline-title">You have new Notification
                                                                            <p class="mt-2">Lorem ipsom dolor sit amet...</p>
                                                                            <p>Lorem ipsom dolor sit amet...</p>
                                                                        </h4>
																		<span class="vertical-timeline-element-date"></span>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<ul class="nav flex-column">
									<li class="nav-item-divider nav-item"></li>
									<li class="nav-item-btn text-center nav-item">
										<button class="btn-shadow btn-wide btn-pill btn btn-focus btn-sm">Mark as Read All</button>
									</li>
								</ul>
							</div>-->
						</div>
						
						<!-- Language Line -->
						<!--<div class="dropdown">
							<button type="button" data-toggle="dropdown" class="p-0 mr-2 btn btn-link"> <span class="icon-wrapper icon-wrapper-alt rounded-circle">
                                    <span class="icon-wrapper-bg bg-focus"></span>
									<span class="language-icon opacity-8 flag large DE"></span>
								</span>
							</button>
							<div tabindex="-1" role="menu" aria-hidden="true" class="rm-pointers dropdown-menu dropdown-menu-right">
								<div class="dropdown-menu-header">
									<div class="dropdown-menu-header-inner pt-4 pb-4 bg-focus">
										<div class="menu-header-image opacity-05" style="background-image: url('{{asset('public/adminAssets/images/dropdown-header/city2.jpg')}}');"></div>
										<div class="menu-header-content text-center text-white">
											<h6 class="menu-header-subtitle mt-0"> Choose Language</h6>
										</div>
									</div>
								</div>
								<h6 tabindex="-1" class="dropdown-header"> Popular Languages</h6>
								<button type="button" tabindex="0" class="dropdown-item"> <span class="mr-3 opacity-8 flag large US"></span> USA</button>
								<button type="button" tabindex="0" class="dropdown-item"> <span class="mr-3 opacity-8 flag large IN"></span> India</button>
								<button type="button" tabindex="0" class="dropdown-item"> <span class="mr-3 opacity-8 flag large FR"></span> France</button>
								<button type="button" tabindex="0" class="dropdown-item"> <span class="mr-3 opacity-8 flag large ES"></span>Spain</button>
							</div>
						</div>-->
					</div>
					
					<div class="header-btn-lg pr-0">
						<div class="widget-content p-0">
							<div class="widget-content-wrapper">
							<div class="widget-content-left header-user-info">
									<div class="widget-heading">{{Auth::user()->name}}</div>
									<!--<div class="widget-subheading">{{Auth::user()->user_type}}</div>-->
								</div>
								<div class="widget-content-left ml-3">
									<a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><img src="{{asset('adminAssets/images/logout-icon.svg')}}" alt="" class="img-fluid"/></a>
									<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
										@csrf
									</form>
								</div>								
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>