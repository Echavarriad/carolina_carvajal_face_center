@if(session()->has('flash.success'))
<section class="content-header">
   <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            {{ session('flash.success') }}
    </div>
 </section>
@elseif (session()->has('flash.warning'))
<section class="content-header">
   <div class="alert alert-warning alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            {{ session('flash.warning') }}
    </div>
 </section>
@elseif(session()->has('flash.error'))
<section class="content-header">
   <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            {{ session('flash.error') }}
    </div>
 </section>
@endif