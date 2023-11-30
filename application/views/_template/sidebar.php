<div class="pcoded-main-container">
  <div class="pcoded-wrapper">
    <nav class="pcoded-navbar">
      <div class="sidebar_toggle"><a href="#"><i class="icon-close icons"></i></a></div>
      <div class="pcoded-inner-navbar main-menu">
        <div class="pcoded-navigatio-lavel" data-i18n="nav.category.navigation"></div>
        <ul class="pcoded-item pcoded-left-item">
          <li class="<?= $this->uri->segment(1) == '' ? 'active' : '' ?>">
            <a href="<?= base_url() ?>">
              <span class="pcoded-micon"><i class="fa fa-file-text"></i><b>B</b></span>
              <span class="pcoded-mtext" data-i18n="nav.dash.main">Documentation</span>
              <span class="pcoded-mcaret"></span>
            </a>
          </li>
          <li class="<?= $this->uri->segment(1) == 'products' ? 'active' : '' ?>">
            <a href="<?= base_url() ?>products?is_selling=true">
              <span class="pcoded-micon"><i class="fa fa-shopping-bag"></i><b>P</b></span>
              <span class="pcoded-mtext" data-i18n="nav.dash.main">Products</span>
              <span class="pcoded-mcaret"></span>
            </a>
          </li>
          <li class="pcoded-hasmenu <?= $this->uri->segment(1) == 'master' ? 'pcoded-trigger active' : '' ?>">
            <a href="javascript:void(0)">
              <span class="pcoded-micon"><i class="fa fa-gears"></i></span>
              <span class="pcoded-mtext" data-i18n="nav.basic-components.main">Master</span>
              <span class="pcoded-mcaret"></span>
            </a>
            <ul class="pcoded-submenu">
              <li class=" <?= ($this->uri->segment(1) == 'master' & $this->uri->segment(2) == 'category') ? 'active' : '' ?>">
                <a href="<?= site_url() ?>master/category">
                  <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                  <span class="pcoded-mtext" data-i18n="nav.basic-components.alert">Category</span>
                  <span class="pcoded-mcaret"></span>
                </a>
              </li>
              <li class=" <?= ($this->uri->segment(1) == 'master' & $this->uri->segment(2) == 'status') ? 'active' : '' ?>">
                <a href="<?= site_url() ?>master/status">
                  <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                  <span class="pcoded-mtext" data-i18n="nav.basic-components.breadcrumbs">Status</span>
                  <span class="pcoded-mcaret"></span>
                </a>
              </li>
            </ul>
          </li>
        </ul>
    </nav>