  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="<?php echo $this->session->userdata('uj_photo');?>" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?php echo $this->session->userdata('uj_fullname');?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- search form -->
      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
        <li class="header">MAIN NAVIGATION</li>
        <li class="active treeview">
            <a href="<?php echo base_url();?>" onClick="load_page(this);return false">
                <i class="fa fa-dashboard"></i> <span>Dashboard</span>
            </a>
        </li>
        <li class="treeview">
            <a href="#">
                <i class="fa fa-book"></i> <span>Barang</span>
                <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                <li><a href="<?php echo base_url('items');?>" onClick="load_page(this);return false"><i class="fa fa-database"></i> Data Barang</a></li>
                <li><a href="<?php echo base_url('items/pinjam');?>" onClick="load_page(this);return false"><i class="fa fa-sign-out"></i> Peminjaman</a></li>
                <li><a href="<?php echo base_url('pengajuan');?>" onClick="load_page(this);return false"><i class="fa fa-shopping-cart"></i> Pengajuan Barang</a></li>
                <li><a href="<?php echo base_url('category');?>" onClick="load_page(this);return false"><i class="fa fa-tags"></i> Kategori Barang</a></li>
                <li><a href="<?php echo base_url('brand');?>" onClick="load_page(this);return false"><i class="fa fa-bookmark"></i> Merek Barang</a></li>
            </ul>
        </li>
        <li class="treeview">
        	<a href="<?php echo base_url('account');?>" onclick="load_page(this);return false">
            	<i class="fa fa-users"></i> <span>Pengguna</span>
            </a>
        </li>
        <li class="treeview">
        	<a href="<?php echo base_url('peminjam');?>" onclick="load_page(this);return false">
            	<i class="fa fa-child"></i> <span>Peminjam</span>
            </a>
        </li>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
