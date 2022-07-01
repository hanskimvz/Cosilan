
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="Responsive Bootstrap 4 Admin &amp; Dashboard Template">
	<meta name="author" content="Bootlab">

	<title>Advanced Inputs - AppStack - Admin &amp; Dashboard Template</title>

	<link rel="preconnect" href="//fonts.gstatic.com/" crossorigin>

	<!-- PICK ONE OF THE STYLES BELOW -->
	<!-- <link href="css/classic.css" rel="stylesheet"> -->
	<!-- <link href="css/corporate.css" rel="stylesheet"> -->
	<!-- <link href="css/modern.css" rel="stylesheet"> -->

	<!-- BEGIN SETTINGS -->
	<!-- You can remove this after picking a style -->
	<style>
		body {
			opacity: 0;
		}
	</style>
	<script src="js/settings.js"></script>
	<!-- END SETTINGS -->
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-120946860-6"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-120946860-6');
</script></head>

<body>
	<div class="wrapper">
		<nav id="sidebar" class="sidebar">
			<div class="sidebar-content ">
				<a class="sidebar-brand" href="index.html">
          <i class="align-middle" data-feather="box"></i>
          <span class="align-middle">AppStack</span>
        </a>

				<ul class="sidebar-nav">
					<li class="sidebar-header">
						Pages
					</li>
					<li class="sidebar-item">
						<a href="#dashboards" data-toggle="collapse" class="sidebar-link collapsed">
              <i class="align-middle" data-feather="sliders"></i> <span class="align-middle">Dashboards</span>
              <span class="sidebar-badge badge badge-primary">5</span>
            </a>
						<ul id="dashboards" class="sidebar-dropdown list-unstyled collapse " data-parent="#sidebar">
							<li class="sidebar-item"><a class="sidebar-link" href="dashboard-default.html">Default</a></li>
							<li class="sidebar-item"><a class="sidebar-link" href="dashboard-analytics.html">Analytics</a></li>
							<li class="sidebar-item"><a class="sidebar-link" href="dashboard-e-commerce.html">E-commerce</a></li>
							<li class="sidebar-item"><a class="sidebar-link" href="dashboard-social.html">Social</a></li>
							<li class="sidebar-item"><a class="sidebar-link" href="dashboard-crypto.html">Crypto</a></li>
						</ul>
					</li>
					<li class="sidebar-item">
						<a href="#pages" data-toggle="collapse" class="sidebar-link collapsed">
              <i class="align-middle" data-feather="layout"></i> <span class="align-middle">Pages</span>
            </a>
						<ul id="pages" class="sidebar-dropdown list-unstyled collapse " data-parent="#sidebar">
							<li class="sidebar-item"><a class="sidebar-link" href="pages-profile.html">Profile</a></li>
							<li class="sidebar-item"><a class="sidebar-link" href="pages-settings.html">Settings</a></li>
							<li class="sidebar-item"><a class="sidebar-link" href="pages-clients.html">Clients</a></li>
							<li class="sidebar-item">
								<a href="#projects" data-toggle="collapse" class="sidebar-link collapsed">
                  Projects
                </a>
								<ul id="projects" class="sidebar-dropdown list-unstyled collapse">
									<li class="sidebar-item">
										<a class="sidebar-link" href="pages-projects-list.html">List</a>
									</li>
									<li class="sidebar-item">
										<a class="sidebar-link" href="pages-projects-detail.html">Detail <span class="sidebar-badge badge badge-primary">New</span></a>
									</li>
								</ul>
							</li>
							<li class="sidebar-item"><a class="sidebar-link" href="pages-invoice.html">Invoice</a></li>
							<li class="sidebar-item"><a class="sidebar-link" href="pages-pricing.html">Pricing</a></li>
							<li class="sidebar-item"><a class="sidebar-link" href="pages-tasks.html">Tasks</a></li>
							<li class="sidebar-item"><a class="sidebar-link" href="pages-chat.html">Chat <span class="sidebar-badge badge badge-primary">New</span></a></li>
							<li class="sidebar-item"><a class="sidebar-link" href="pages-blank.html">Blank Page</a></li>
						</ul>
					</li>
					<li class="sidebar-item">
						<a href="#auth" data-toggle="collapse" class="sidebar-link collapsed">
              <i class="align-middle" data-feather="users"></i> <span class="align-middle">Auth</span>
              <span class="sidebar-badge badge badge-secondary">Special</span>
            </a>
						<ul id="auth" class="sidebar-dropdown list-unstyled collapse " data-parent="#sidebar">
							<li class="sidebar-item"><a class="sidebar-link" href="pages-sign-in.html">Sign In</a></li>
							<li class="sidebar-item"><a class="sidebar-link" href="pages-sign-up.html">Sign Up</a></li>
							<li class="sidebar-item"><a class="sidebar-link" href="pages-reset-password.html">Reset Password</a></li>
							<li class="sidebar-item"><a class="sidebar-link" href="pages-404.html">404 Page</a></li>
							<li class="sidebar-item"><a class="sidebar-link" href="pages-500.html">500 Page</a></li>
						</ul>
					</li>
					<li class="sidebar-item">
						<a href="#layouts" data-toggle="collapse" class="sidebar-link collapsed">
              <i class="align-middle" data-feather="monitor"></i> <span class="align-middle">Layouts</span>
            </a>
						<ul id="layouts" class="sidebar-dropdown list-unstyled collapse " data-parent="#sidebar">
							<li class="sidebar-item"><a class="sidebar-link" href="layouts-sidebar-sticky.html">Sticky Sidebar</a></li>
							<li class="sidebar-item"><a class="sidebar-link" href="layouts-sidebar-collapsed.html">Collapsed Sidebar</a></li>
							<li class="sidebar-item"><a class="sidebar-link" href="layouts-boxed.html">Boxed Layout</a></li>
							<li class="sidebar-item"><a class="sidebar-link" href="dashboard-analytics.html?theme=classic">Classic Theme</a></li>
							<li class="sidebar-item"><a class="sidebar-link" href="dashboard-analytics.html?theme=corporate">Corporate Theme <span class="sidebar-badge badge badge-primary">New</span></a></li>
							<li class="sidebar-item"><a class="sidebar-link" href="dashboard-analytics.html?theme=modern">Modern Theme <span class="sidebar-badge badge badge-primary">New</span></a></li>
						</ul>
					</li>
					<li class="sidebar-item">
						<a href="#documentation" data-toggle="collapse" class="sidebar-link collapsed">
              <i class="align-middle" data-feather="book-open"></i> <span class="align-middle">Documentation</span>
            </a>
						<ul id="documentation" class="sidebar-dropdown list-unstyled collapse " data-parent="#sidebar">
							<li class="sidebar-item"><a class="sidebar-link" href="docs-introduction.html">Introduction</a></li>
							<li class="sidebar-item"><a class="sidebar-link" href="docs-installation.html">Getting Started</a></li>
							<li class="sidebar-item"><a class="sidebar-link" href="docs-plugins.html">Plugins</a></li>
							<li class="sidebar-item"><a class="sidebar-link" href="docs-changelog.html">Changelog</a></li>
						</ul>
					</li>

					<li class="sidebar-header">
						Tools & Components
					</li>
					<li class="sidebar-item">
						<a href="#ui" data-toggle="collapse" class="sidebar-link collapsed">
              <i class="align-middle" data-feather="grid"></i> <span class="align-middle">UI Elements</span>
            </a>
						<ul id="ui" class="sidebar-dropdown list-unstyled collapse " data-parent="#sidebar">
							<li class="sidebar-item"><a class="sidebar-link" href="ui-alerts.html">Alerts</a></li>
							<li class="sidebar-item"><a class="sidebar-link" href="ui-buttons.html">Buttons</a></li>
							<li class="sidebar-item"><a class="sidebar-link" href="ui-cards.html">Cards</a></li>
							<li class="sidebar-item"><a class="sidebar-link" href="ui-carousel.html">Carousel</a></li>
							<li class="sidebar-item"><a class="sidebar-link" href="ui-embed-video.html">Embed Video</a></li>
							<li class="sidebar-item"><a class="sidebar-link" href="ui-general.html">General <span class="sidebar-badge badge badge-info">10+</span></a></li>
							<li class="sidebar-item"><a class="sidebar-link" href="ui-grid.html">Grid</a></li>
							<li class="sidebar-item"><a class="sidebar-link" href="ui-modals.html">Modals</a></li>
							<li class="sidebar-item"><a class="sidebar-link" href="ui-tabs.html">Tabs</a></li>
							<li class="sidebar-item"><a class="sidebar-link" href="ui-typography.html">Typography</a></li>
						</ul>
					</li>
					<li class="sidebar-item">
						<a href="#icons" data-toggle="collapse" class="sidebar-link collapsed">
              <i class="align-middle" data-feather="heart"></i> <span class="align-middle">Icons</span>
              <span class="sidebar-badge badge badge-info">1500+</span>
            </a>
						<ul id="icons" class="sidebar-dropdown list-unstyled collapse " data-parent="#sidebar">
							<li class="sidebar-item"><a class="sidebar-link" href="icons-feather.html">Feather</a></li>
							<li class="sidebar-item"><a class="sidebar-link" href="icons-font-awesome.html">Font Awesome</a></li>
						</ul>
					</li>
					<li class="sidebar-item">
						<a href="#forms" data-toggle="collapse" class="sidebar-link collapsed">
              <i class="align-middle" data-feather="check-square"></i> <span class="align-middle">Forms</span>
            </a>
						<ul id="forms" class="sidebar-dropdown list-unstyled collapse " data-parent="#sidebar">
							<li class="sidebar-item"><a class="sidebar-link" href="forms-layouts.html">Layouts</a></li>
							<li class="sidebar-item"><a class="sidebar-link" href="forms-basic-inputs.html">Basic Inputs</a></li>
							<li class="sidebar-item"><a class="sidebar-link" href="forms-input-groups.html">Input Groups</a></li>
						</ul>
					</li>
					<li class="sidebar-item">
						<a class="sidebar-link" href="tables-bootstrap.html">
              <i class="align-middle" data-feather="list"></i> <span class="align-middle">Tables</span>
            </a>
					</li>

					<li class="sidebar-header">
						Plugin & Addons
					</li>
					<li class="sidebar-item active">
						<a href="#form-plugins" data-toggle="collapse" class="sidebar-link">
              <i class="align-middle" data-feather="check-square"></i> <span class="align-middle">Form Plugins</span>
            </a>
						<ul id="form-plugins" class="sidebar-dropdown list-unstyled collapse show" data-parent="#sidebar">
							<li class="sidebar-item active"><a class="sidebar-link" href="forms-advanced-inputs.html">Advanced Inputs</a></li>
							<li class="sidebar-item"><a class="sidebar-link" href="forms-editors.html">Editors</a></li>
							<li class="sidebar-item"><a class="sidebar-link" href="forms-validation.html">Validation</a></li>
							<li class="sidebar-item"><a class="sidebar-link" href="forms-wizard.html">Wizard</a></li>
						</ul>
					</li>
					<li class="sidebar-item">
						<a href="#datatables" data-toggle="collapse" class="sidebar-link collapsed">
              <i class="align-middle" data-feather="list"></i> <span class="align-middle">DataTables</span>
            </a>
						<ul id="datatables" class="sidebar-dropdown list-unstyled collapse " data-parent="#sidebar">
							<li class="sidebar-item"><a class="sidebar-link" href="tables-datatables-responsive.html">Responsive Table</a></li>
							<li class="sidebar-item"><a class="sidebar-link" href="tables-datatables-buttons.html">Table with Buttons</a></li>
							<li class="sidebar-item"><a class="sidebar-link" href="tables-datatables-column-search.html">Column Search</a></li>
							<li class="sidebar-item"><a class="sidebar-link" href="tables-datatables-multi.html">Multi Selection</a></li>
							<li class="sidebar-item"><a class="sidebar-link" href="tables-datatables-ajax.html">Ajax Sourced Data</a></li>
						</ul>
					</li>

					<li class="sidebar-item">
						<a href="#charts" data-toggle="collapse" class="sidebar-link collapsed">
              <i class="align-middle" data-feather="pie-chart"></i> <span class="align-middle">Charts</span>
              <span class="sidebar-badge badge badge-primary">New</span>
            </a>
						<ul id="charts" class="sidebar-dropdown list-unstyled collapse " data-parent="#sidebar">
							<li class="sidebar-item"><a class="sidebar-link" href="charts-chartjs.html">Chart.js</a></li>
							<li class="sidebar-item"><a class="sidebar-link" href="charts-apexcharts.html">ApexCharts <span class="sidebar-badge badge badge-primary">New</span></a></li>
						</ul>
					</li>
					<li class="sidebar-item">
						<a class="sidebar-link" href="notifications.html">
              <i class="align-middle" data-feather="bell"></i> <span class="align-middle">Notifications</span>
            </a>
					</li>
					<li class="sidebar-item">
						<a href="#maps" data-toggle="collapse" class="sidebar-link collapsed">
              <i class="align-middle" data-feather="map-pin"></i> <span class="align-middle">Maps</span>
            </a>
						<ul id="maps" class="sidebar-dropdown list-unstyled collapse " data-parent="#sidebar">
							<li class="sidebar-item"><a class="sidebar-link" href="maps-google.html">Google Maps</a></li>
							<li class="sidebar-item"><a class="sidebar-link" href="maps-vector.html">Vector Maps</a></li>
						</ul>
					</li>
					<li class="sidebar-item">
						<a class="sidebar-link" href="calendar.html">
              <i class="align-middle" data-feather="calendar"></i> <span class="align-middle">Calendar</span>
            </a>
					</li>
					<li class="sidebar-item">
						<a href="#multi" data-toggle="collapse" class="sidebar-link collapsed">
              <i class="align-middle" data-feather="share-2"></i> <span class="align-middle">Multi Level</span>
            </a>
						<ul id="multi" class="sidebar-dropdown list-unstyled collapse" data-parent="#sidebar">
							<li class="sidebar-item">
								<a href="#multi-2" data-toggle="collapse" class="sidebar-link collapsed">
                  Two Levels
                </a>
								<ul id="multi-2" class="sidebar-dropdown list-unstyled collapse">
									<li class="sidebar-item">
										<a class="sidebar-link" href="#">Item 1</a>
										<a class="sidebar-link" href="#">Item 2</a>
									</li>
								</ul>
							</li>
							<li class="sidebar-item">
								<a href="#multi-3" data-toggle="collapse" class="sidebar-link collapsed">
                  Three Levels
                </a>
								<ul id="multi-3" class="sidebar-dropdown list-unstyled collapse">
									<li class="sidebar-item">
										<a href="#multi-3-1" data-toggle="collapse" class="sidebar-link collapsed">
                      Item 1
                    </a>
										<ul id="multi-3-1" class="sidebar-dropdown list-unstyled collapse">
											<li class="sidebar-item">
												<a class="sidebar-link" href="#">Item 1</a>
												<a class="sidebar-link" href="#">Item 2</a>
											</li>
										</ul>
									</li>
									<li class="sidebar-item">
										<a class="sidebar-link" href="#">Item 2</a>
									</li>
								</ul>
							</li>
						</ul>
					</li>
				</ul>

				<div class="sidebar-bottom d-none d-lg-block">
					<div class="media">
						<img class="rounded-circle mr-3" src="img/avatars/avatar.jpg" alt="Chris Wood" width="40" height="40">
						<div class="media-body">
							<h5 class="mb-1">Chris Wood</h5>
							<div>
								<i class="fas fa-circle text-success"></i> Online
							</div>
						</div>
					</div>
				</div>

			</div>
		</nav>

		<div class="main">
			<nav class="navbar navbar-expand navbar-light bg-white">
				<a class="sidebar-toggle d-flex mr-2">
          <i class="hamburger align-self-center"></i>
        </a>

				<form class="form-inline d-none d-sm-inline-block">
					<input class="form-control form-control-no-border mr-sm-2" type="text" placeholder="Search projects..." aria-label="Search">
				</form>

				<div class="navbar-collapse collapse">
					<ul class="navbar-nav ml-auto">
						<li class="nav-item dropdown">
							<a class="nav-icon dropdown-toggle" href="#" id="messagesDropdown" data-toggle="dropdown">
								<div class="position-relative">
									<i class="align-middle" data-feather="message-circle"></i>
									<span class="indicator">4</span>
								</div>
							</a>
							<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right py-0" aria-labelledby="messagesDropdown">
								<div class="dropdown-menu-header">
									<div class="position-relative">
										4 New Messages
									</div>
								</div>
								<div class="list-group">
									<a href="#" class="list-group-item">
										<div class="row no-gutters align-items-center">
											<div class="col-2">
												<img src="img/avatars/avatar-5.jpg" class="avatar img-fluid rounded-circle" alt="Ashley Briggs">
											</div>
											<div class="col-10 pl-2">
												<div class="text-dark">Ashley Briggs</div>
												<div class="text-muted small mt-1">Nam pretium turpis et arcu. Duis arcu tortor.</div>
												<div class="text-muted small mt-1">15m ago</div>
											</div>
										</div>
									</a>
									<a href="#" class="list-group-item">
										<div class="row no-gutters align-items-center">
											<div class="col-2">
												<img src="img/avatars/avatar-2.jpg" class="avatar img-fluid rounded-circle" alt="Carl Jenkins">
											</div>
											<div class="col-10 pl-2">
												<div class="text-dark">Carl Jenkins</div>
												<div class="text-muted small mt-1">Curabitur ligula sapien euismod vitae.</div>
												<div class="text-muted small mt-1">2h ago</div>
											</div>
										</div>
									</a>
									<a href="#" class="list-group-item">
										<div class="row no-gutters align-items-center">
											<div class="col-2">
												<img src="img/avatars/avatar-4.jpg" class="avatar img-fluid rounded-circle" alt="Stacie Hall">
											</div>
											<div class="col-10 pl-2">
												<div class="text-dark">Stacie Hall</div>
												<div class="text-muted small mt-1">Pellentesque auctor neque nec urna.</div>
												<div class="text-muted small mt-1">4h ago</div>
											</div>
										</div>
									</a>
									<a href="#" class="list-group-item">
										<div class="row no-gutters align-items-center">
											<div class="col-2">
												<img src="img/avatars/avatar-3.jpg" class="avatar img-fluid rounded-circle" alt="Bertha Martin">
											</div>
											<div class="col-10 pl-2">
												<div class="text-dark">Bertha Martin</div>
												<div class="text-muted small mt-1">Aenean tellus metus, bibendum sed, posuere ac, mattis non.</div>
												<div class="text-muted small mt-1">5h ago</div>
											</div>
										</div>
									</a>
								</div>
								<div class="dropdown-menu-footer">
									<a href="#" class="text-muted">Show all messages</a>
								</div>
							</div>
						</li>
						<li class="nav-item dropdown">
							<a class="nav-icon dropdown-toggle" href="#" id="alertsDropdown" data-toggle="dropdown">
								<div class="position-relative">
									<i class="align-middle" data-feather="bell-off"></i>
								</div>
							</a>
							<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right py-0" aria-labelledby="alertsDropdown">
								<div class="dropdown-menu-header">
									4 New Notifications
								</div>
								<div class="list-group">
									<a href="#" class="list-group-item">
										<div class="row no-gutters align-items-center">
											<div class="col-2">
												<i class="text-danger" data-feather="alert-circle"></i>
											</div>
											<div class="col-10">
												<div class="text-dark">Update completed</div>
												<div class="text-muted small mt-1">Restart server 12 to complete the update.</div>
												<div class="text-muted small mt-1">2h ago</div>
											</div>
										</div>
									</a>
									<a href="#" class="list-group-item">
										<div class="row no-gutters align-items-center">
											<div class="col-2">
												<i class="text-warning" data-feather="bell"></i>
											</div>
											<div class="col-10">
												<div class="text-dark">Lorem ipsum</div>
												<div class="text-muted small mt-1">Aliquam ex eros, imperdiet vulputate hendrerit et.</div>
												<div class="text-muted small mt-1">6h ago</div>
											</div>
										</div>
									</a>
									<a href="#" class="list-group-item">
										<div class="row no-gutters align-items-center">
											<div class="col-2">
												<i class="text-primary" data-feather="home"></i>
											</div>
											<div class="col-10">
												<div class="text-dark">Login from 192.186.1.1</div>
												<div class="text-muted small mt-1">8h ago</div>
											</div>
										</div>
									</a>
									<a href="#" class="list-group-item">
										<div class="row no-gutters align-items-center">
											<div class="col-2">
												<i class="text-success" data-feather="user-plus"></i>
											</div>
											<div class="col-10">
												<div class="text-dark">New connection</div>
												<div class="text-muted small mt-1">Anna accepted your request.</div>
												<div class="text-muted small mt-1">12h ago</div>
											</div>
										</div>
									</a>
								</div>
								<div class="dropdown-menu-footer">
									<a href="#" class="text-muted">Show all notifications</a>
								</div>
							</div>
						</li>
						<li class="nav-item dropdown">
							<a class="nav-flag dropdown-toggle" href="#" id="languageDropdown" data-toggle="dropdown">
                <img src="img/flags/us.png" alt="English" />
              </a>
							<div class="dropdown-menu dropdown-menu-right" aria-labelledby="languageDropdown">
								<a class="dropdown-item" href="#">
                  <img src="img/flags/us.png" alt="English" width="20" class="align-middle mr-1" />
                  <span class="align-middle">English</span>
                </a>
								<a class="dropdown-item" href="#">
                  <img src="img/flags/es.png" alt="Spanish" width="20" class="align-middle mr-1" />
                  <span class="align-middle">Spanish</span>
                </a>
								<a class="dropdown-item" href="#">
                  <img src="img/flags/de.png" alt="German" width="20" class="align-middle mr-1" />
                  <span class="align-middle">German</span>
                </a>
								<a class="dropdown-item" href="#">
                  <img src="img/flags/nl.png" alt="Dutch" width="20" class="align-middle mr-1" />
                  <span class="align-middle">Dutch</span>
                </a>
							</div>
						</li>
						<li class="nav-item dropdown">
							<a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-toggle="dropdown">
                <i class="align-middle" data-feather="settings"></i>
              </a>

							<a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" data-toggle="dropdown">
                <img src="img/avatars/avatar.jpg" class="avatar img-fluid rounded-circle mr-1" alt="Chris Wood" /> <span class="text-dark">Chris Wood</span>
              </a>
							<div class="dropdown-menu dropdown-menu-right">
								<a class="dropdown-item" href="pages-profile.html"><i class="align-middle mr-1" data-feather="user"></i> Profile</a>
								<a class="dropdown-item" href="#"><i class="align-middle mr-1" data-feather="pie-chart"></i> Analytics</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="pages-settings.html">Settings & Privacy</a>
								<a class="dropdown-item" href="#">Help</a>
								<a class="dropdown-item" href="#">Sign out</a>
							</div>
						</li>
					</ul>
				</div>
			</nav>

			<main class="content">
				<div class="container-fluid p-0">

					<h1 class="h3 mb-3">Advanced Inputs</h1>

					<div class="row">
						<div class="col-12 col-lg-5 col-xxl-6 d-flex">
							<div class="card flex-fill">
								<div class="card-header">
									<h5 class="card-title">Select2</h5>
									<h6 class="card-subtitle text-muted">The jQuery replacement for select boxes.</h6>
								</div>
								<div class="card-body">
									<div class="mb-3">
										<select class="form-control select2" data-toggle="select2">
            <optgroup label="Alaskan/Hawaiian Time Zone">
              <option value="AK">Alaska</option>
              <option value="HI">Hawaii</option>
            </optgroup>
            <optgroup label="Pacific Time Zone">
              <option value="CA">California</option>
              <option value="NV">Nevada</option>
              <option value="OR">Oregon</option>
              <option value="WA">Washington</option>
            </optgroup>
            <optgroup label="Mountain Time Zone">
              <option value="AZ">Arizona</option>
              <option value="CO">Colorado</option>
              <option value="ID">Idaho</option>
              <option value="MT">Montana</option>
              <option value="NE">Nebraska</option>
              <option value="NM">New Mexico</option>
              <option value="ND">North Dakota</option>
              <option value="UT">Utah</option>
              <option value="WY">Wyoming</option>
            </optgroup>
            <optgroup label="Central Time Zone">
              <option value="AL">Alabama</option>
              <option value="AR">Arkansas</option>
              <option value="IL">Illinois</option>
              <option value="IA">Iowa</option>
              <option value="KS">Kansas</option>
              <option value="KY">Kentucky</option>
              <option value="LA">Louisiana</option>
              <option value="MN">Minnesota</option>
              <option value="MS">Mississippi</option>
              <option value="MO">Missouri</option>
              <option value="OK">Oklahoma</option>
              <option value="SD">South Dakota</option>
              <option value="TX">Texas</option>
              <option value="TN">Tennessee</option>
              <option value="WI">Wisconsin</option>
            </optgroup>
            <optgroup label="Eastern Time Zone">
              <option value="CT">Connecticut</option>
              <option value="DE">Delaware</option>
              <option value="FL">Florida</option>
              <option value="GA">Georgia</option>
              <option value="IN">Indiana</option>
              <option value="ME">Maine</option>
              <option value="MD">Maryland</option>
              <option value="MA">Massachusetts</option>
              <option value="MI">Michigan</option>
              <option value="NH">New Hampshire</option>
              <option value="NJ">New Jersey</option>
              <option value="NY">New York</option>
              <option value="NC">North Carolina</option>
              <option value="OH">Ohio</option>
              <option value="PA">Pennsylvania</option>
              <option value="RI">Rhode Island</option>
              <option value="SC">South Carolina</option>
              <option value="VT">Vermont</option>
              <option value="VA">Virginia</option>
              <option value="WV">West Virginia</option>
            </optgroup>
          </select>
									</div>

									<div class="mb-3">
										<select class="form-control select2" data-toggle="select2" multiple>
            <optgroup label="Alaskan/Hawaiian Time Zone">
              <option value="AK">Alaska</option>
              <option value="HI">Hawaii</option>
            </optgroup>
            <optgroup label="Pacific Time Zone">
              <option value="CA">California</option>
              <option value="NV">Nevada</option>
              <option value="OR">Oregon</option>
              <option value="WA">Washington</option>
            </optgroup>
            <optgroup label="Mountain Time Zone">
              <option value="AZ">Arizona</option>
              <option value="CO">Colorado</option>
              <option value="ID">Idaho</option>
              <option value="MT">Montana</option>
              <option value="NE">Nebraska</option>
              <option value="NM">New Mexico</option>
              <option value="ND">North Dakota</option>
              <option value="UT">Utah</option>
              <option value="WY">Wyoming</option>
            </optgroup>
            <optgroup label="Central Time Zone">
              <option value="AL">Alabama</option>
              <option value="AR">Arkansas</option>
              <option value="IL">Illinois</option>
              <option value="IA">Iowa</option>
              <option value="KS">Kansas</option>
              <option value="KY">Kentucky</option>
              <option value="LA">Louisiana</option>
              <option value="MN">Minnesota</option>
              <option value="MS">Mississippi</option>
              <option value="MO">Missouri</option>
              <option value="OK">Oklahoma</option>
              <option value="SD">South Dakota</option>
              <option value="TX">Texas</option>
              <option value="TN">Tennessee</option>
              <option value="WI">Wisconsin</option>
            </optgroup>
            <optgroup label="Eastern Time Zone">
              <option value="CT">Connecticut</option>
              <option value="DE">Delaware</option>
              <option value="FL">Florida</option>
              <option value="GA">Georgia</option>
              <option value="IN">Indiana</option>
              <option value="ME">Maine</option>
              <option value="MD">Maryland</option>
              <option value="MA">Massachusetts</option>
              <option value="MI">Michigan</option>
              <option value="NH">New Hampshire</option>
              <option value="NJ">New Jersey</option>
              <option value="NY">New York</option>
              <option value="NC">North Carolina</option>
              <option value="OH">Ohio</option>
              <option value="PA">Pennsylvania</option>
              <option value="RI">Rhode Island</option>
              <option value="SC">South Carolina</option>
              <option value="VT">Vermont</option>
              <option value="VA">Virginia</option>
              <option value="WV">West Virginia</option>
            </optgroup>
          </select>
									</div>

									<div>
										<select class="form-control select2" data-toggle="select2" multiple>
            <option value="one">First</option>
            <option value="two" disabled="disabled">Second (disabled)</option>
            <option value="three">Third</option>
          </select>
									</div>
								</div>
							</div>
						</div>

						<div class="col-12 col-lg-7 col-xxl-6 d-flex">
							<div class="card flex-fill">
								<div class="card-header">
									<h5 class="card-title">Date Range Picker</h5>
									<h6 class="card-subtitle text-muted">Component for choosing date ranges, dates and times.</h6>
								</div>
								<div class="card-body">
									<div class="row">
										<div class="col-12 col-xl-4">
											<div class="form-group">
												<label class="form-label">Date Range</label>
												<input class="form-control" type="text" name="daterange" value="01/01/2018 - 01/15/2018" />
											</div>
										</div>

										<div class="col-12 col-xl-8">
											<div class="form-group">
												<label class="form-label">Date Range with Times</label>
												<input class="form-control" type="text" name="datetimes" />
											</div>
										</div>

										<div class="col-12 col-xl-4">
											<div class="form-group mb-xl-0">
												<label class="form-label">Single Date Picker</label>
												<input class="form-control" type="text" name="datesingle" />
											</div>
										</div>

										<div class="col-12 col-xl-8">
											<div class="form-group mb-xl-0">
												<label class="form-label">Predefined Date Ranges</label>
												<div id="reportrange" class="overflow-hidden form-control">
													<i class="far fa-calendar"></i>&nbsp;
													<span></span> <i class="fas fa-caret-down"></i>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-12">
							<div class="card">
								<div class="card-header">
									<h5 class="card-title">Date Time Picker</h5>
									<h6 class="card-subtitle text-muted">Date and time picker designed to integrate into your Bootstrap project.</h6>
								</div>
								<div class="card-body">
									<div class="row">
										<div class="col-12 col-xl-6">
											<div class="form-group">
												<label class="form-label">Minimum Setup</label>
												<div class="input-group date" id="datetimepicker-minimum" data-target-input="nearest">
													<input type="text" class="form-control datetimepicker-input" data-target="#datetimepicker-minimum" />
													<div class="input-group-append" data-target="#datetimepicker-minimum" data-toggle="datetimepicker">
														<div class="input-group-text"><i class="fa fa-calendar"></i></div>
													</div>
												</div>
											</div>
											<div class="form-group">
												<label class="form-label">Time Only</label>
												<div class="input-group date" id="datetimepicker-time" data-target-input="nearest">
													<input type="text" class="form-control datetimepicker-input" data-target="#datetimepicker-time" />
													<div class="input-group-append" data-target="#datetimepicker-time" data-toggle="datetimepicker">
														<div class="input-group-text"><i class="fa fa-calendar"></i></div>
													</div>
												</div>
											</div>
										</div>
										<div class="col-12 col-xl-6">
											<div class="form-group">
												<label class="form-label">Date Only</label>
												<div class="input-group date" id="datetimepicker-date" data-target-input="nearest">
													<input type="text" class="form-control datetimepicker-input" data-target="#datetimepicker-date" />
													<div class="input-group-append" data-target="#datetimepicker-date" data-toggle="datetimepicker">
														<div class="input-group-text"><i class="fa fa-calendar"></i></div>
													</div>
												</div>
											</div>

											<div class="form-group">
												<label class="form-label">View Mode</label>
												<div class="input-group date" id="datetimepicker-view-mode" data-target-input="nearest">
													<input type="text" class="form-control datetimepicker-input" data-target="#datetimepicker-view-mode" />
													<div class="input-group-append" data-target="#datetimepicker-view-mode" data-toggle="datetimepicker">
														<div class="input-group-text"><i class="fa fa-calendar"></i></div>
													</div>
												</div>
											</div>
										</div>
									</div>

								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-12">
							<div class="card">
								<div class="card-header">
									<h5 class="card-title">Input Masks</h5>
									<h6 class="card-subtitle text-muted">jQuery Plugin to make masks on form fields.</h6>
								</div>
								<div class="card-body">
									<div class="row">
										<div class="col-12 col-lg-6">
											<div class="form-group">
												<label>Date</label>
												<input type="text" class="form-control" data-mask="00/00/0000">
												<span class="font-13 text-muted">e.g "DD/MM/YYYY"</span>
											</div>
											<div class="form-group">
												<label>Hour</label>
												<input type="text" class="form-control" data-mask="00:00:00">
												<span class="font-13 text-muted">e.g "HH:MM:SS"</span>
											</div>
											<div class="form-group">
												<label>Date & Hour</label>
												<input type="text" class="form-control" data-mask="00/00/0000 00:00:00">
												<span class="font-13 text-muted">e.g "DD/MM/YYYY HH:MM:SS"</span>
											</div>
											<div class="form-group">
												<label>ZIP Code</label>
												<input type="text" class="form-control" data-mask="00000-000">
												<span class="font-13 text-muted">e.g "xxxxx-xxx"</span>
											</div>
											<div class="form-group">
												<label>Crazy Zip Code</label>
												<input type="text" class="form-control" data-mask="0-00-00-00">
												<span class="font-13 text-muted">e.g "x-xx-xx-xx"</span>
											</div>
											<div class="form-group">
												<label>Money</label>
												<input type="text" class="form-control" data-mask="000.000.000.000.000,00" data-reverse="true">
												<span class="font-13 text-muted">e.g "Your money"</span>
											</div>
											<div class="form-group">
												<label>Money 2</label>
												<input type="text" class="form-control" data-mask="#.##0,00" data-reverse="true">
												<span class="font-13 text-muted">e.g "#.##0,00"</span>
											</div>
										</div>
										<div class="col-12 col-lg-6">
											<div class="form-group">
												<label>Telephone</label>
												<input type="text" class="form-control" data-mask="0000-0000">
												<span class="font-13 text-muted">e.g "xxxx-xxxx"</span>
											</div>
											<div class="form-group">
												<label>Telephone with Code Area</label>
												<input type="text" class="form-control" data-mask="(00) 0000-0000">
												<span class="font-13 text-muted">e.g "(xx) xxxx-xxxx"</span>
											</div>
											<div class="form-group">
												<label>US Telephone</label>
												<input type="text" class="form-control" data-mask="(000) 000-0000">
												<span class="font-13 text-muted">e.g "(xxx) xxx-xxxx"</span>
											</div>
											<div class="form-group">
												<label>São Paulo Celphones</label>
												<input type="text" class="form-control" data-mask="(00) 00000-0000">
												<span class="font-13 text-muted">e.g "(xx) xxxxx-xxxx"</span>
											</div>
											<div class="form-group">
												<label>CPF</label>
												<input type="text" class="form-control" data-mask="000.000.000-00" data-reverse="true">
												<span class="font-13 text-muted">e.g "xxx.xxx.xxxx-xx"</span>
											</div>
											<div class="form-group">
												<label>CNPJ</label>
												<input type="text" class="form-control" data-mask="00.000.000/0000-00" data-reverse="true">
												<span class="font-13 text-muted">e.g "xx.xxx.xxx/xxxx-xx"</span>
											</div>
											<div class="form-group">
												<label>IP Address</label>
												<input type="text" class="form-control" data-mask="099.099.099.099" data-reverse="true">
												<span class="font-13 text-muted">e.g "xxx.xxx.xxx.xxx"</span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

				</div>
			</main>

			<footer class="footer">
				<div class="container-fluid">
					<div class="row text-muted">
						<div class="col-6 text-left">
							<ul class="list-inline">
								<li class="list-inline-item">
									<a class="text-muted" href="#">Support</a>
								</li>
								<li class="list-inline-item">
									<a class="text-muted" href="#">Help Center</a>
								</li>
								<li class="list-inline-item">
									<a class="text-muted" href="#">Privacy</a>
								</li>
								<li class="list-inline-item">
									<a class="text-muted" href="#">Terms of Service</a>
								</li>
							</ul>
						</div>
						<div class="col-6 text-right">
							<p class="mb-0">
								&copy; 2019 - <a href="index.html" class="text-muted">AppStack</a>
							</p>
						</div>
					</div>
				</div>
			</footer>
		</div>
	</div>

	<script src="js/app.js"></script>

	<script>
		$(function() {
			// Select2
			$(".select2").each(function() {
				$(this)
					.wrap("<div class=\"position-relative\"></div>")
					.select2({
						placeholder: "Select value",
						dropdownParent: $(this).parent()
					});
			})
			// Daterangepicker
			$("input[name=\"daterange\"]").daterangepicker({
				opens: "left"
			});
			$("input[name=\"datetimes\"]").daterangepicker({
				timePicker: true,
				opens: "left",
				startDate: moment().startOf("hour"),
				endDate: moment().startOf("hour").add(32, "hour"),
				locale: {
					format: "M/DD hh:mm A"
				}
			});
			$("input[name=\"datesingle\"]").daterangepicker({
				singleDatePicker: true,
				showDropdowns: true
			});
			// Datetimepicker
			$('#datetimepicker-minimum').datetimepicker();
			$('#datetimepicker-view-mode').datetimepicker({
				viewMode: 'years'
			});
			$('#datetimepicker-time').datetimepicker({
				format: 'LT'
			});
			$('#datetimepicker-date').datetimepicker({
				format: 'L'
			});
			var start = moment().subtract(29, "days");
			var end = moment();

			function cb(start, end) {
				$("#reportrange span").html(start.format("MMMM D, YYYY") + " - " + end.format("MMMM D, YYYY"));
			}
			$("#reportrange").daterangepicker({
				startDate: start,
				endDate: end,
				ranges: {
					"Today": [moment(), moment()],
					"Yesterday": [moment().subtract(1, "days"), moment().subtract(1, "days")],
					"Last 7 Days": [moment().subtract(6, "days"), moment()],
					"Last 30 Days": [moment().subtract(29, "days"), moment()],
					"This Month": [moment().startOf("month"), moment().endOf("month")],
					"Last Month": [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")]
				}
			}, cb);
			cb(start, end);
		});
	</script>
</body>

</html>