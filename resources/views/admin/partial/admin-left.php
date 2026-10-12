<div class="page-sidebar-wrapper">

    <div class="page-sidebar navbar-collapse collapse">

        <!-- add "navbar-no-scroll" class to disable the scrolling of the sidebar menu -->

        <!-- BEGIN SIDEBAR MENU -->

        <ul class="page-sidebar-menu" data-auto-scroll="true" data-slide-speed="200">

            <li class="sidebar-toggler-wrapper">



                <div class="sidebar-toggler hidden-phone">

                </div>



            </li>



            <li class="start <?= in_array('dashboard', Request::segments()) ? 'active' : '' ?> ">

                <a href="<?= URL::to('admin/dashboard') ?>">

                    <i class="fa fa-home"></i>

                    <span class="title">

                        Dashboard 

                    </span>

                    <span class="selected">

                    </span>

                </a>

            </li>

            

            

            

            <li class=" <?= in_array('settings', Request::segments()) ? 'active' : '' ?> ">

                <a href="<?= URL::to('admin/settings') ?>">

                    <i class="fa fa-cog"></i>

                    <span class="title">

                        Setting 

                    </span>

                    <span class="selected">

                    </span>

                </a>

            </li>

            

            <li class=" <?= in_array('banners', Request::segments()) ? 'active' : '' ?> ">



                <a href="<?= URL::to('admin/banners') ?>">



                    <i class="fa fa-image"></i>



                    <span class="title">



                        Banners 



                    </span>



                    <span class="selected">



                    </span>



                </a>



            </li>



              

            



            



            <li class=" <?= in_array('services', Request::segments()) ? 'active' : '' ?> ">



                <a href="<?= URL::to('admin/services') ?>">



                    <i class="fa fa-folder"></i>



                    <span class="title">



                        Features



                    </span>



                    <span class="selected">



                    </span>



                </a>



            </li>

			
            <li class=" <?= in_array('members', Request::segments()) ? 'active' : '' ?> ">



                <a href="<?= URL::to('admin/members') ?>">



                    <i class="fa fa-user"></i>



                    <span class="title">



                       Sponsors



                    </span>



                    <span class="selected">



                    </span>



                </a>



            </li>

            
			<li class=" <?= in_array('category', Request::segments()) ? 'active' : '' ?> ">



                <a href="<?= URL::to('admin/category') ?>">



                    <i class="fa fa-folder"></i>



                    <span class="title">



                        Category 



                    </span>



                    <span class="selected">



                    </span>



                </a>



            </li>
			<li class=" <?= in_array('products', Request::segments()) ? 'active' : '' ?> ">



                <a href="<?= URL::to('admin/products') ?>">



                    <i class="fa fa-folder"></i>



                    <span class="title">



                        Products 



                    </span>



                    <span class="selected">



                    </span>



                </a>



            </li>

            
			

			<li class=" <?= in_array('projects', Request::segments()) ? 'active' : '' ?> ">



                <a href="<?= URL::to('admin/projects') ?>">



                    <i class="fa fa-file-text-o"></i>



                    <span class="title">



                        Our Portfolio 



                    </span>



                    <span class="selected">



                    </span>



                </a>



            </li>
            
			<?php 	
				$quote_count = App\Model\Quote::whereRaw('status ="Yes"')->count();
			?>
            <li class=" <?= in_array('quotes', Request::segments()) ? 'active' : '' ?> ">
                <a href="<?= URL::to('admin/quotes') ?>">
                    <i class="fa fa-file-text-o"></i>
                    <span class="title">
                        Quotes 
						<?php if($quote_count>0){?><span class="badge"><?=$quote_count?></span><?php } ?>


                    </span>



                    <span class="selected">



                    </span>



                </a>



            </li>
			

            <li class=" <?= in_array('pages', Request::segments()) ? 'active' : '' ?> ">



                <a href="<?= URL::to('admin/pages') ?>">



                    <i class="fa fa-file-text-o"></i>



                    <span class="title">



                        CMS 



                    </span>



                    <span class="selected">



                    </span>



                </a>



            </li>

            

            <li >

                <a href="<?= URL::to('admin/logout') ?>">

                    <i class="fa fa-key"></i>

                    <span class="title">Logout</span>

                </a>

            </li>

        </ul>

        <!-- END SIDEBAR MENU -->

    </div>

</div>