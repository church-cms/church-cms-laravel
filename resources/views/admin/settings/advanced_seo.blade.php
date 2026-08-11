<form method="POST" action="{{ url('/admin/settings/advancedseo') }}" enctype="multipart/form-data">
    @csrf
    <div class="px-3 py-3 mx-2">
        <div class="my-3 px-2 py-1">
            <div class="">
                <div class="w-full lg:w-1/4 md:w-1/4">
                    <label for="title" class="tw-form-label">SEO Title</label>
                </div>
                <div class="w-full lg:w-2/5 md:w-2/5 my-2">
                    <input type="text" name="sitetitle" id="sitetitle" value="{!! old('sitetitle', \config::get('settings.sitetitle')) !!}"
                        class="tw-form-control w-full" placeholder="SEO Title">
                    <span class="text-danger text-xs font-semibold">{{ $errors->first('sitetitle') }}</span>
                </div>
            </div>
        </div>

        <div class="my-3 px-2 py-1">
            <div class="">
                <div class="w-full lg:w-1/4 md:w-1/4">
                    <label for="site_description" class="tw-form-label">SEO Description</label>
                </div>
                <div class="w-full lg:w-2/5 md:w-2/5 my-2">
                    <input type="text" name="site_description" id="site_description" class="tw-form-control w-full"
                        placeholder="SEO Description" value="{!! old('site_description', \config::get('settings.site_description')) !!}">
                    <span class="text-danger text-xs font-semibold">{{ $errors->first('site_description') }}</span>
                </div>
            </div>
        </div>

        <div class="my-3 px-2 py-1">
            <div class="">
                <div class="w-full lg:w-1/4 md:w-1/4">
                    <label for="site_keyword" class="tw-form-label">Meta Keywords</label>
                </div>
                <div class="w-full lg:w-2/5 md:w-2/5 my-2">
                    <input type="text" name="site_keyword" id="site_keyword" value="{!! old('site_keyword', \config::get('settings.site_keyword')) !!}"
                        class="tw-form-control w-full" placeholder="Meta Keywords">
                </div>
                <p class="text-gray-500 text-xs">Separate Each Keyword With A Comma</p>
                <span class="text-danger text-xs font-semibold">{{ $errors->first('site_keyword') }}</span>
            </div>
        </div>

        <div class="my-3 px-2 py-1">
            <div class="">
                <div class="w-full lg:w-1/4 md:w-1/4">
                    <label for="header_code" class="tw-form-label">Header Code</label>
                </div>
                <div class="w-full lg:w-2/5 md:w-2/5 my-2">
                    <textarea type="textarea" name="header_code" id="header_code" rows="3"
                        class="tw-form-control w-full" placeholder="Header Code">{!! old('header_code', \config::get('settings.header_code')) !!}</textarea>
                </div>
                <span class="text-danger text-xs font-semibold">{{ $errors->first('header_code') }}</span>
            </div>
        </div>

        <div class="my-3 px-2 py-1">
            <div class="">
                <div class="w-full lg:w-1/4 md:w-1/4">
                    <label for="footer_code" class="tw-form-label">Footer Code</label>
                </div>
                <div class="w-full lg:w-2/5 md:w-2/5 my-2">
                    <textarea type="textarea" name="footer_code" id="footer_code" rows="3"
                        class="tw-form-control w-full" placeholder="Footer Code">{!! old('footer_code', \config::get('settings.footer_code')) !!}</textarea>
                </div>
                <span class="text-danger text-xs font-semibold">{{ $errors->first('footer_code') }}</span>
            </div>
        </div>

        <div class="mb-6 px-2">
            <input id="submit"
                class="btn btn-primary blue-bg text-white rounded px-3 py-1 text-sm font-medium cursor-pointer"
                name="submit" type="submit" value="Submit">
        </div>
    </div>
</form>
