<div class="fixed-sidebar-left">
   <ul class="nav navbar-nav side-nav nicescroll-bar">
      <li class="navigation-header">
         <span>
            {{ Auth::user()->first_name }} 
            &nbsp; 
            {{ Auth::user()->last_name }}
         </span> 
      </li>

      @if ((!Auth::user()->hasRole('superadministrator')) && ($user->company))
      <li class="navigation-header">
            <span class="pb-0">{{ $user->company->name }}</span>
      </li>

      <li><hr class="light-grey-hr mb-10 mt-10"/></li>
      @endif

      <li>
         <a href="{{ route('home') }}" class="active">
            <div class="pull-left">
               <i class="zmdi zmdi-landscape mr-20"></i>
               <span class="right-nav-text">Dashboard</span>
            </div>
            <div class="pull-right">
            </div>
            <div class="clearfix"></div>
         </a>
      </li>

      @if (Auth::user()->hasRole('superadministrator'))
      <li>
         <a href="javascript:void(0);" data-toggle="collapse" data-target="#companies_dr">
            <div class="pull-left">
               <i class="zmdi zmdi-apps mr-20"></i>
               <span class="right-nav-text">Companies </span>
            </div>
            <div class="pull-right">
               <i class="zmdi zmdi-caret-down"></i>
            </div>
            <div class="clearfix"></div>
         </a>
         <ul id="companies_dr" class="collapse collapse-level-1">
            
            <li>
               <a href="{{ route('companies.create') }}">
                  <i class="zmdi zmdi-accounts-add mr-10"></i>
                  <span class="right-nav-text">Create Company</span>
               </a>
            </li>
            <li>
               <a href="{{ route('companies.index') }}">
                  <i class="fa fa-users mr-10"></i>
                  <span class="right-nav-text">Manage Companies</span>
               </a>
            </li>

         </ul>
      </li>

      <li>
         <a href="javascript:void(0);" data-toggle="collapse" data-target="#mpesapaybills_dr">
            <div class="pull-left">
               <i class="zmdi zmdi-lock-outline mr-20"></i>
               <span class="right-nav-text">Mpesa Paybills </span>
            </div>
            <div class="pull-right">
               <i class="zmdi zmdi-caret-down"></i>
            </div>
            <div class="clearfix"></div>
         </a>
         <ul id="mpesapaybills_dr" class="collapse collapse-level-1">
            
            <li>
               <a href="{{ route('mpesa-paybills.create') }}">
                  <i class="zmdi zmdi-accounts-add mr-10"></i>
                  <span class="right-nav-text">Create Mpesa Paybill</span>
               </a>
            </li>
            <li>
               <a href="{{ route('mpesa-paybills.index') }}">
                  <i class="fa fa-users mr-10"></i>
                  <span class="right-nav-text">Manage Mpesa Paybills</span>
               </a>
            </li>

         </ul>
      </li>

      <li>
         <a href="javascript:void(0);" data-toggle="collapse" data-target="#perms_dr">
            <div class="pull-left">
               <i class="zmdi zmdi-lock-outline mr-20"></i>
               <span class="right-nav-text">Permissions </span>
            </div>
            <div class="pull-right">
               <i class="zmdi zmdi-caret-down"></i>
            </div>
            <div class="clearfix"></div>
         </a>
         <ul id="perms_dr" class="collapse collapse-level-1">
            
            <li>
               <a href="{{ route('permissions.create') }}">
                  <i class="zmdi zmdi-accounts-add mr-10"></i>
                  <span class="right-nav-text">Create Permission</span>
               </a>
            </li>
            <li>
               <a href="{{ route('permissions.index') }}">
                  <i class="fa fa-users mr-10"></i>
                  <span class="right-nav-text">Manage Permissions</span>
               </a>
            </li>

         </ul>
      </li>

      <li>
         <a href="javascript:void(0);" data-toggle="collapse" data-target="#roles_dr">
            <div class="pull-left">
               <i class="zmdi zmdi-lock-outline mr-20"></i>
               <span class="right-nav-text">Roles </span>
            </div>
            <div class="pull-right">
               <i class="zmdi zmdi-caret-down"></i>
            </div>
            <div class="clearfix"></div>
         </a>
         <ul id="roles_dr" class="collapse collapse-level-1">
            
            <li>
               <a href="{{ route('roles.create') }}">
                  <i class="zmdi zmdi-accounts-add mr-10"></i>
                  <span class="right-nav-text">Create Role</span>
               </a>
            </li>
            <li>
               <a href="{{ route('roles.index') }}">
                  <i class="fa fa-users mr-10"></i>
                  <span class="right-nav-text">Manage Roles</span>
               </a>
            </li>

         </ul>
      </li>

      @endif

      <li>
         <a href="javascript:void(0);" data-toggle="collapse" data-target="#groups_dr">
            <div class="pull-left">
               <i class="zmdi zmdi-apps mr-20"></i>
               <span class="right-nav-text">User Groups </span>
            </div>
            <div class="pull-right">
               <i class="zmdi zmdi-caret-down"></i>
            </div>
            <div class="clearfix"></div>
         </a>
         <ul id="groups_dr" class="collapse collapse-level-1">
            
            <li>
               <a href="{{ route('groups.create') }}">
                  <i class="zmdi zmdi-accounts-add mr-10"></i>
                  <span class="right-nav-text">Create Group</span>
               </a>
            </li>
            <li>
               <a href="{{ route('groups.index') }}">
                  <i class="fa fa-users mr-10"></i>
                  <span class="right-nav-text">Manage Groups</span>
               </a>
            </li>

         </ul>
      </li>

      <li>
         <a href="javascript:void(0);" data-toggle="collapse" data-target="#users_dr">
            <div class="pull-left">
               <i class="zmdi zmdi-accounts mr-20"></i>
               <span class="right-nav-text">User Accounts </span>
            </div>
            <div class="pull-right">
               <i class="zmdi zmdi-caret-down"></i>
            </div>
            <div class="clearfix"></div>
         </a>
         <ul id="users_dr" class="collapse collapse-level-1">
            
            <li>
               <a href="{{ route('users.create') }}">
                  <i class="zmdi zmdi-account-add mr-10"></i>
                  <span class="right-nav-text">Create Single</span>
               </a>
            </li>
            <li>
               <a href="{{ route('bulk-users.create') }}">
                  <i class="zmdi zmdi-accounts-add mr-10"></i>
                  <span class="right-nav-text">Create Bulk</span>
               </a>
            </li>
            <li>
               <a href="{{ route('users.index') }}">
                  <i class="zmdi zmdi-accounts-list mr-10"></i>
                  <span class="right-nav-text">Manage Users</span>
               </a>
            </li>

         </ul>
      </li>

      

      <li><hr class="light-grey-hr mb-10"/></li>

      <li>
         <a href="javascript:void(0);" data-toggle="collapse" data-target="#sms_dr">
            <div class="pull-left">
               <i class="zmdi zmdi-smartphone mr-20"></i>
               <span class="right-nav-text">SMS</span>
            </div>
            <div class="pull-right">
               <i class="zmdi zmdi-caret-down"></i>
            </div>
            <div class="clearfix"></div>
         </a>
         <ul id="sms_dr" class="collapse collapse-level-1 two-col-list">
            <li>
               <a href="javascript:void(0);" data-toggle="collapse" data-target="#bulksms_dr">
                  <div class="pull-left">
                     <span class="right-nav-text">Bulk SMS</span>
                  </div>
                  <div class="pull-right">
                     <i class="zmdi zmdi-caret-down"></i>
                  </div>
                  <div class="clearfix"></div>
               </a>
               <ul id="bulksms_dr" class="collapse collapse-level-1 two-col-list">
                  <li>
                     <a href="{{ route('smsoutbox.create') }}">Send SMS</a>
                  </li>
                  <li>
                     <a href="{{ route('scheduled-smsoutbox.index') }}">Scheduled SMS</a>
                  </li>
                  <li>
                     <a href="{{ route('smsoutbox.index') }}">My Outbox</a>
                  </li>
                  <!-- <li>
                     <a href="modals.php">Analytics</a>
                  </li> -->
               </ul>
            </li>
            <!-- <li>
               <a href="javascript:void(0);" data-toggle="collapse" data-target="#premsms_dr">
                  <div class="pull-left">
                     <span class="right-nav-text">Premium SMS</span>
                  </div>
                  <div class="pull-right">
                     <i class="zmdi zmdi-caret-down"></i>
                  </div>
                  <div class="clearfix"></div>
               </a>
               <ul id="premsms_dr" class="collapse collapse-level-1 two-col-list">
                  <li>
                     <a href="panels_wells.php">Outbox</a>
                  </li>
                  <li>
                     <a href="modals.php">Analytics</a>
                  </li>
               </ul>
            </li> -->
            <li>
               <a href="#">Inbox</a>
            </li>
            <!-- <li>
               <a href="notifications.php">Short Codes</a>
            </li> -->
            
         </ul>
      </li>

      <li>
         <a href="javascript:void(0);" data-toggle="collapse" data-target="#mpesa_dr">
            <div class="pull-left">
               <i class="zmdi zmdi-money mr-20"></i>
               <span class="right-nav-text">Mpesa</span>
            </div>
            <div class="pull-right">
               <i class="zmdi zmdi-caret-down"></i>
            </div>
            <div class="clearfix"></div>
         </a>
         <ul id="mpesa_dr" class="collapse collapse-level-1 two-col-list">
            <li>
               <a href="{{ route('mpesa-incoming.index') }}">
                  <div class="pull-left">
                     <span class="right-nav-text">Manage Mpesa</span>
                  </div>
                  <div class="pull-right">
                  </div>
                  <div class="clearfix"></div>
               </a>
               
            </li>
            
            <!-- <li>
               <a href="#">Inbox</a>
            </li> -->
           
         </ul>
      </li>

      <li>
         <a href="javascript:void(0);" data-toggle="collapse" data-target="#voice_dr">
            <div class="pull-left">
               <i class="zmdi zmdi-phone mr-20"></i>
               <span class="right-nav-text">Voice</span>
            </div>
            <div class="pull-right">
               <i class="zmdi zmdi-caret-down"></i>
            </div>
            <div class="clearfix"></div>
         </a>
         <ul id="voice_dr" class="collapse collapse-level-1 two-col-list">
            <li>
               <a href="#">Phone Numbers</a>
            </li>
            <li>
               <a href="#">Create a Number</a>
            </li>
            
         </ul>
      </li>

      <li>
         <a href="javascript:void(0);" data-toggle="collapse" data-target="#ussd_dr">
            <div class="pull-left">
               <i class="zmdi zmdi-gps mr-20"></i>
               <span class="right-nav-text">USSD</span>
            </div>
            <div class="pull-right">
               <i class="zmdi zmdi-caret-down"></i>
            </div>
            <div class="clearfix"></div>
         </a>
         <ul id="ussd_dr" class="collapse collapse-level-1 two-col-list">
            <li>
               <a href="#">Service Codes</a>
            </li>
            <li>
               <a href="#">Push Request</a>
            </li>
            <li>
               <a href="#">Analytics</a>
            </li>
            
         </ul>
      </li>

      <li>
         <a href="javascript:void(0);" data-toggle="collapse" data-target="#airtime_dr">
            <div class="pull-left">
               <i class="zmdi zmdi-view-web mr-20"></i>
               <span class="right-nav-text">Airtime</span>
            </div>
            <div class="pull-right">
               <i class="zmdi zmdi-caret-down"></i>
            </div>
            <div class="clearfix"></div>
         </a>
         <ul id="airtime_dr" class="collapse collapse-level-1 two-col-list">
            <li>
               <a href="#">Transactions</a>
            </li>
            <li>
               <a href="#">Callback URL</a>
            </li>
            <li>
               <a href="#">Analytics</a>
            </li>
            
         </ul>
      </li>

      <li><hr class="light-grey-hr mb-10"/></li>

      <li>
         <a href="javascript:void(0);" data-toggle="collapse" data-target="#account_dr">
            <div class="pull-left">
               <i class="zmdi zmdi-account mr-20"></i>
               <span class="right-nav-text">My Account</span>
            </div>
            <div class="pull-right">
               <i class="zmdi zmdi-caret-down"></i>
            </div>
            <div class="clearfix"></div>
         </a>
         <ul id="account_dr" class="collapse collapse-level-1 two-col-list">
            <li>
               <a href="{{ route('user.profile') }}">
                  Profile
               </a>
            </li>
            <li>
               <a href="#" data-toggle="modal" data-target="#password-modal">Change Password</a>
            </li>
            
         </ul>
      </li>

      <li>
            <a href="{{ route('logout') }}"
                onclick="event.preventDefault();
                         document.getElementById('logout-form').submit();">
               <div class="pull-left">
                  <i class="zmdi zmdi-power mr-20"></i>
                  <span class="right-nav-text">Log Out</span>
               </div>
               
               <div class="clearfix"></div>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>

       </li>

   </ul>
</div>



<!-- /.modal -->
<div id="password-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h5 class="modal-title">Change Password</h5>
         </div>
         <div class="modal-body">
            <form method="POST"> 
               {{ csrf_field() }}
               <div class="form-group">
                  <label for="old_password" class="control-label mb-10">Old Password:</label>
                  <input type="text" class="form-control" id="old_password" name="old_password">
               </div>
               <hr>
               <div class="form-group">
                  <label for="new_password1" class="control-label mb-10">New Password:</label>
                  <input type="text" class="form-control" id="new_password1" name="new_password1">
               </div>
               <div class="form-group">
                  <label for="new_password2" class="control-label mb-10">New Password Repeat:</label>
                  <input type="text" class="form-control" id="new_password2" name="new_password2">
               </div>
            </form>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-danger">Save changes</button>
         </div>
      </div>
   </div>
</div>
<!-- Button trigger modal -->