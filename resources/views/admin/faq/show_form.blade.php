<div class="bg-white p-4 w-full lg:w-full lg:mr-8 md:mr-8 custom-table py-4">
    <table class="w-full">
        <tr class="border-t-2 border-b-2">
            <td class="py-3 px-2">Category Name</td>
            <td class="py-3 px-2">{{ ucwords($faq->faqCategory->name) }}</td>
        </tr>

        <tr class="border-t-2 border-b-2">
            <td class="py-3 px-2">Question</td>
            <td class="py-3 px-2">{{ $faq->question }} </td>
        </tr>

        <tr class="border-t-2 border-b-2">
            <td class="py-3 px-2">Answer</td>
            <td class="py-3 px-2">{{ $faq->answer }}</a></td>
        </tr>

        <tr class="border-t-2 border-b-2">
            <td class="py-3 px-2">Order</td>
            <td class="py-3 px-2">{{ $faq->order }}</td>
        </tr>
    </table>
</div>
