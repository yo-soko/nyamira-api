<div class="sidebar-contact ">
    <div class="toggle-theme"  data-bs-toggle="offcanvas" data-bs-target="#theme-setting"><i class="fa fa-cog fa-w-16 fa-spin"></i></div>
    </div>
    <div class="sidebar-themesettings offcanvas offcanvas-end" id="theme-setting">
    <div class="offcanvas-header d-flex align-items-center justify-content-between bg-dark">
        <div>
            <h3 class="mb-1 text-white">Theme Customizer</h3>
            <p class="text-light">Choose your favourite theme.</p>
        </div>
        <a href="#" class="custom-btn-close d-flex align-items-center justify-content-center text-white"  data-bs-dismiss="offcanvas"><i class="ti ti-x"></i></a>
    </div>
    <div class="themecard-body offcanvas-body">
        <div class="accordion accordion-customicon1 accordions-items-seperate" id="settingtheme">
          

            <div class="accordion-item border px-3">
                <h2 class="accordion-header">
                    <button class="accordion-button text-dark fs-16 px-0 py-3 bg-transparent" type="button" data-bs-toggle="collapse" data-bs-target="#modesetting" aria-expanded="true">
                       Theme Mode
                    </button>
                </h2>
                <div id="modesetting" class="accordion-collapse collapse show">
                    <div class="accordion-body px-0 py3 border-top">
                       <div class="d-flex align-items-center">
                            <div class="theme-mode flex-fill text-center w-100 me-3">
                                <input type="radio" name="theme" id="lightTheme" value="light" checked>
                                <label for="lightTheme" class="rounded fw-medium w-100">                            
                                    <span class="d-inline-flex rounded me-2"><i class="ti ti-sun-filled"></i></span>Light
                                </label>
                            </div>
                            <div class="theme-mode flex-fill text-center w-100 me-3">
                                <input type="radio" name="theme" id="darkTheme" value="dark" >
                                <label for="darkTheme" class="rounded fw-medium w-100">                         
                                    <span class="d-inline-flex rounded me-2"><i class="ti ti-moon-filled"></i></span>Dark
                                </label>
                            </div>
                            <div class="theme-mode flex-fill w-100 text-center">
                                <input type="radio" name="theme" id="systemTheme" value="system">
                                <label for="systemTheme" class="rounded fw-medium w-100">                         
                                    <span class="d-inline-flex rounded me-2"><i class="ti ti-device-laptop"></i></span>System 
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>    	    
             
           
        </div> 
    </div>
    <div class="p-3 pt-0">
        <div class="row gx-3">
            <div class="col-6">
                <a href="#" id="resetbutton" class="btn btn-light close-theme w-100"><i class="ti ti-restore me-1"></i>Reset</a>
            </div>
          
        </div>
    </div>    
</div>
