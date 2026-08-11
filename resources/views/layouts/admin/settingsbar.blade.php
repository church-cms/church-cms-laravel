<div class="w-full lg:w-48 md:w-48 settings-sidebar h-auto">
    <ul class="list-reset">
        <li class="{{ Request::segment(3) == 'generalsettings' ? 'active' : '' }}">
            <a href="{{ url('admin/settings/generalsettings/') }}" class="no-underline">General</a>
        </li>
        <li class="{{ Request::segment(3) == 'maintenancesettings' ? 'active' : '' }}">
            <a href="{{ url('admin/settings/maintenancesettings/') }}" class="no-underline">Maintenance</a>
        </li>
        <li class="{{ Request::segment(3) == 'seodetail' ? 'active' : '' }}">
            <a href="{{ url('admin/settings/seodetail') }}" class="no-underline">SEO</a>
        </li>
        <li class="{{ Request::segment(3) == 'htmlcode' ? 'active' : '' }}">
            <a href="{{ url('admin/settings/htmlcode') }}" class="no-underline">HTML / JS Code</a>
        </li>
        <li class="{{ Request::segment(3) == 'opengraph' ? 'active' : '' }}">
            <a href="{{ url('admin/settings/opengraph') }}" class="no-underline">Open Graph Tags</a>
        </li>
        <li class="{{ Request::segment(3) == 'socialmedia' ? 'active' : '' }}">
            <a href="{{ url('admin/settings/socialmedia') }}" class="no-underline">Social Media</a>
        </li>
        <li class="{{ Request::segment(3) == 'contact' ? 'active' : '' }}">
            <a href="{{ url('admin/settings/contact') }}" class="no-underline">Contact</a>
        </li>
        <li class="{{ Request::segment(3) == 'location' ? 'active' : '' }}">
            <a href="{{ url('admin/settings/location') }}" class="no-underline">Location &amp; Map</a>
        </li>
    </ul>
</div>
