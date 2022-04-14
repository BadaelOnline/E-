<template>
<div>
   <div class="sidebar-container">
  <div class="sidebar-logo">
    Dashbord
  </div>
  <ul class="sidebar-navigation">
    <li class="header">Navigation</li>
    <li>
      <a href="#">
        <i class="fa fa-home" aria-hidden="true"></i> Stores
      </a>
    </li>
    <li>
      <a href="#">
        <i class="fa fa-tachometer" aria-hidden="true"></i> Doctors
      </a>
    </li>
    <li class="header">Another Menu</li>
    <li>
      <a href="#">
        <i class="fa fa-cog" aria-hidden="true"></i> Settings
      </a>
    </li>
    <li>
      <a href="#">
        <i class="fa fa-info-circle" aria-hidden="true"></i> LogOut
      </a>
    </li>
  </ul>
</div>

<div class="content-container">

  <div class="container-fluid">

    <!-- Main component for a primary marketing message or call to action -->
    <div class="jumbotron">
      <h1>Dashboard</h1>
    </div>

  </div>
</div>
      <!--========= HEADER ==========-->
    	<router-view/>
    </div>
</template>

<script>
    export default {
    }
</script>
