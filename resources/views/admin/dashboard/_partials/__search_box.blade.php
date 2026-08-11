 <div class="w-full  dashboard-filter">
     <portal-target name="search"></portal-target>
     <portal-target name="memberfilter"></portal-target>
     <search-filter url="{{ url('/') }}" searchquery="{{ $query }}"></search-filter>
 </div>
