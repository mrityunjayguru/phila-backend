	<style>
.scrollbar-sidebar {
    overflow-y: scroll;
    float: left;
    overflow-y: scroll;
    margin-bottom: 25px;
}

.scrollbar-sidebar::-webkit-scrollbar {
    width: 1px;
    background-color: #CD081C;
}
	</style>
	<div class="app-sidebar sidebar-shadow">
	    <div class="scrollbar-sidebar">
	        <div class="app-sidebar__inner">
	            <ul class="vertical-nav-menu">
	                <li class="app-sidebar__heading">
	                    <a class="{{ Request::is('backend/dashboard*') ? 'mm-active' : '' }}"
	                        href="{{ route('dashboard') }}"><img src="{{asset('adminAssets/images/dashboard-icon.svg')}}"
	                            alt="" class="img-fluid metismenu-icon">{{trans('sidebar.dashboard')}}</a>
	                </li>

	                @if(Settings::UserPermission('ticket-list'))
	                <li class="app-sidebar__heading">
	                    <a class="{{ Request::is('backend/tickets*') ? 'mm-active' : '' }}"
	                        href="{{ route('tickets.index') }}"><img src="{{asset('adminAssets/images/tickets-icon.svg')}}"
	                            alt="" class="img-fluid metismenu-icon">{{trans('sidebar.tickets')}}</a>
	                </li>
	                @endif

	                @if(Settings::UserPermission('slider-list'))
	                <li class="app-sidebar__heading">
	                    <!--<a class="{{ Request::is('backend/sliders*') ? 'mm-active' : '' }}" href="{{ route('sliders.index') }}"><img src="{{asset('adminAssets/images/sliders-icon.svg')}}" alt="" class="img-fluid metismenu-icon">{{trans('sidebar.sliders')}}</a>-->
	                    <a class="{{ Request::is('backend/sliders*') ? 'mm-active' : '' }}"
	                        href="{{ route('sliders.edit', '1') }}"><img
	                            src="{{asset('adminAssets/images/sliders-icon.svg')}}" alt=""
	                            class="img-fluid metismenu-icon">{{trans('sidebar.sliders')}}</a>
	                </li>
	                @endif

	                @if(Settings::UserPermission('place-list'))
	                <li class="app-sidebar__heading">
	                    <a href="javascript:void(0);"><img src="{{asset('adminAssets/images/manage-icon.svg')}}" alt=""
	                            class="img-fluid metismenu-icon"> Manage<i
	                            class="metismenu-state-icon pe-7s-angle-down caret-left"></i></a>
	                    <ul>
	                        <li><a class="{{ Request::is('backend/places/landmark*') ? 'mm-active' : '' }}"
	                                href="{{ route('page.places',['landmark']) }}"> <i
	                                    class="metismenu-icon pe-7s-graph"></i>Things To do</a></li>
	                        <li><a class="{{ Request::is('backend/places/dining*') ? 'mm-active' : '' }}"
	                                href="{{ route('page.places',['dining']) }}"> <i
	                                    class="metismenu-icon pe-7s-graph"></i>{{trans('sidebar.dining')}}</a></li>
	                        <li><a class="{{ Request::is('backend/places/attraction*') ? 'mm-active' : '' }}"
	                                href="{{ route('page.places',['attraction']) }}"> <i
	                                    class="metismenu-icon pe-7s-graph"></i>Hotels</a></li>
	                        <li><a class="{{ Request::is('backend/places/shopping*') ? 'mm-active' : '' }}"
	                                href="{{ route('page.places',['shopping']) }}"> <i
	                                    class="metismenu-icon pe-7s-graph"></i>{{trans('sidebar.shopping')}}</a></li>
	                    </ul>
	                </li>
	                @endif

	                @if(Settings::UserPermission('offer-list'))
	                <li class="app-sidebar__heading">
	                    <a class="{{ Request::is('backend/offers*') ? 'mm-active' : '' }}"
	                        href="{{ route('offers.index') }}"><img src="{{asset('adminAssets/images/offer-icon.png')}}"
	                            alt="" class="img-fluid metismenu-icon"> {{trans('sidebar.offers')}}</a>
	                </li>
	                @endif

	                @if(Settings::UserPermission('stop-list'))
	                <li class="app-sidebar__heading">
	                    <a class="{{ Request::is('backend/stops*') ? 'mm-active' : '' }}"
	                        href="{{ route('stops.index') }}"><img src="{{asset('adminAssets/images/stops-icon.svg')}}"
	                            alt="" class="img-fluid metismenu-icon">{{trans('sidebar.stops')}}</a>
	                </li>
	                @endif


	                @if(Settings::UserPermission('audio-list'))
	                <li class="app-sidebar__heading">
	                    <a href="javascript:void(0);"><img src="{{asset('adminAssets/images/Audio.svg')}}" alt=""
	                            class="img-fluid metismenu-icon">{{trans('sidebar.audio')}}</a>
	                    <ul>
	                        <li><a class="{{ Request::is('backend/landing-page*') ? 'mm-active' : '' }}"
	                                href="{{ route('landing-page') }}"> <i
	                                    class="metismenu-icon pe-7s-graph"></i>{{trans('sidebar.landing_page')}}</a>
							</li>	
							@php 
								$landingPage = App\Models\LandingPage::get();
								// echo "<pre>"; print_r($landingPage);
							@endphp
							@foreach($landingPage as $item)
								<li>
									<a href="javascript:void(0);">{{$item->title}}</a>
									<ul>
										@if($item->is_code_dependecy == 'yes')
											<li>
												<a class="{{ Request::is('backend/codes'.'/'.$item->id.'*') ? 'mm-active' : '' }}" href="{{ route('codes.index',$item->id) }}">
													<i class="metismenu-icon pe-7s-graph"></i>{{trans('sidebar.tickets')}}
												</a>
											</li>
										@endif

										<li><a class="{{ Request::is('backend/sample-audio'.'/'.$item->id.'*') ? 'mm-active' : '' }}"
											href="{{ route('sample-audio',$item->id) }}"> <i
												class="metismenu-icon pe-7s-graph"></i>{{trans('sidebar.sample_audio')}}</a></li>

										<li><a class="{{ Request::is('backend/audio'.'/'.$item->id.'*') ? 'mm-active' : '' }}"
											href="{{ route('audio',$item->id) }}"> <i
												class="metismenu-icon pe-7s-graph"></i>{{trans('sidebar.trigger_points')}}</a></li>
									</ul>
								</li>
							@endforeach
	                    </ul>
	                </li>
	                @endif

	                @if(Settings::UserPermission('bus-list'))
	                <li class="app-sidebar__heading">
	                    <a href="javascript:void(0);"><img src="{{asset('adminAssets/images/buses-icon.svg')}}" alt=""
	                            class="img-fluid metismenu-icon">{{trans('sidebar.vehicles')}}</a>
	                    <ul>
	                        <li><a class="{{ Request::is('backend/buses*') ? 'mm-active' : '' }}"
	                                href="{{ route('buses.index') }}"> <i
	                                    class="metismenu-icon pe-7s-graph"></i>{{trans('sidebar.list')}}</a></li>
	                        <li><a class="" href="{{ route('trackBackend') }}" target="_blank"> <i
	                                    class="metismenu-icon pe-7s-graph"></i>{{trans('sidebar.track_now')}}</a></li>
	                    </ul>
	                </li>
	                @endif

	                @if(Settings::UserPermission('timing-management'))
	                <li class="app-sidebar__heading">
	                    <a class="{{ Request::is('backend/timings*') ? 'mm-active' : '' }}"
	                        href="{{ route('timings.index') }}"><img src="{{asset('adminAssets/images/timings-icon.svg')}}"
	                            alt="" class="img-fluid metismenu-icon">Timings</a>
	                </li>
	                @endif

	                @if(Settings::UserPermission('custom-map-management'))
	                <li class="app-sidebar__heading">
	                    <a class="{{ Request::is('backend/custom-map*') ? 'mm-active' : '' }}"
	                        href="{{ route('custom-map.index') }}"><img
	                            src="{{asset('adminAssets/images/location-manage-icon.svg')}}" alt=""
	                            class="img-fluid metismenu-icon">{{trans('sidebar.custom-map')}}</a>
	                </li>
	                @endif

	                @if(Settings::UserPermission('notification-management'))
	                <li class="app-sidebar__heading">
	                    <a class="{{ Request::is('backend/send-notification*') ? 'mm-active' : '' }}"
	                        href="{{ route('send-notification.index') }}"><img
	                            src="{{asset('adminAssets/images/group-395.png')}}" alt=""
	                            class="img-fluid metismenu-icon">Notification</a>
	                </li>
	                @endif

	                @if(Settings::UserPermission('cms-management') || Settings::UserPermission('faq-list'))
	                <li class="app-sidebar__heading">
	                    <a href="javascript:void(0);"><img src="{{asset('adminAssets/images/manage-icon.svg')}}" alt=""
	                            class="img-fluid metismenu-icon"> CMS<i
	                            class="metismenu-state-icon pe-7s-angle-down caret-left"></i></a>
	                    <ul>
	                        @if(Settings::UserPermission('cms-management'))
	                        <li><a class="{{ Request::is('backend/about*') ? 'mm-active' : '' }}"
	                                href="{{ route('about.index') }}"> <i class="metismenu-icon pe-7s-graph"></i>About
	                                Us</a></li>
	                        @endif
	                        @if(Settings::UserPermission('faq-list'))
	                        <li><a class="{{ Request::is('backend/faq-topics*') ? 'mm-active' : '' }}"
	                                href="{{ route('faq-topics.index') }}"> <i class="metismenu-icon pe-7s-graph"></i>FAQ
	                                Topics</a></li>
	                        <li><a class="{{ Request::is('backend/faqs*') ? 'mm-active' : '' }}"
	                                href="{{ route('faqs.index') }}"> <i class="metismenu-icon pe-7s-graph"></i>FAQs</a>
	                        </li>
	                        @endif
	                    </ul>
	                </li>
	                @endif

	                @if(Settings::UserPermission('user-list'))
	                <li class="app-sidebar__heading">
	                    <a class="{{ Request::is('backend/manage/superAdmin*') ? 'mm-active' : '' }}"
	                        href="{{ route('user.management',['superAdmin']) }}"><img
	                            src="{{asset('adminAssets/images/group-397.png')}}" alt=""
	                            class="img-fluid metismenu-icon">{{trans('sidebar.users')}}</a>
	                </li>
	                @endif

	                @if(Settings::UserPermission('general-settings-list') || Settings::UserPermission('change-password'))
	                <li class="app-sidebar__heading">
	                    <a href="javascript:void(0);"> <img src="{{asset('adminAssets/images/settings-icon.svg')}}" alt=""
	                            class="img-fluid metismenu-icon"> Settings<i
	                            class="metismenu-state-icon pe-7s-angle-down caret-left"></i></a>
	                    <ul>
	                        @if(Settings::UserPermission('general-settings-list'))
	                        <li><a class="{{ Request::is('backend/general-settings*') ? 'mm-active' : '' }}"
	                                href="{{ route('general-settings') }}"><i class="metismenu-icon"></i>General
	                                Settings</a></li>
	                        @endif
	                        @if(Settings::UserPermission('change-password'))
	                        <li><a class="{{ Request::is('backend/change-password*') ? 'mm-active' : '' }}"
	                                href="{{ route('change_password') }}"><i class="metismenu-icon"></i>Change Password</a>
	                        </li>
	                        @endif
	                    </ul>
	                </li>
	                @endif
	            </ul>
	        </div>
	    </div>
	</div>