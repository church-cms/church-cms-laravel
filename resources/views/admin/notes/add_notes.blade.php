<div class="w-full lg:w-4/6 md:w-4/6 flex-1 p-4 bg-white shadow">
    @include('partials.message')
    <form action="" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
        @include('partials.message')
        <addnotes></addnotes>


    </form>
</div>
