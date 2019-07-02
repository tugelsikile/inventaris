  <header class="main-header">
    <!-- Logo -->
    <a href="<?php echo base_url('');?>" class="logo">
    	<img src="<?php echo base_url('assets/img/logo.png');?>" height="50" />
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="<?php echo base_url('assets/img/user.png');?>" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php echo $this->session->userdata('inv_name');?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="<?php echo base_url('assets/img/user.png');?>" class="img-circle" alt="User Image">

                <p>
                  <?php echo $this->session->userdata('inv_name');?>
                </p>
              </li>
              <!-- Menu Body
              <li class="user-body">
                <div class="row">
                  <div class="col-xs-4 text-center">
                    <a href="#">Followers</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Sales</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Friends</a>
                  </div>
                </div>
              </li> -->
              
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="<?php echo base_url('account/profile/'.$this->session->userdata('uj_username'));?>" class="btn btn-default btn-flat" onClick="load_page(this);return false">Profile</a>
                </div>
                <div class="pull-right">
                  <a href="<?php echo base_url('account/signout');?>" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-download"></i></a>
          </li>
        </ul>
      </div>
    </nav>
  </header>
