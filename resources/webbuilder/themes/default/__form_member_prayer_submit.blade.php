<form action="{{ route('web.prayer.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Your Prayer Request <span class="text-red-500">*</span>
                    </label>
                    <textarea name="text" rows="4" required minlength="10" maxlength="500"
                              placeholder="Share what you'd like the community to pray for..."
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 resize-none">{{ old('text') }}</textarea>
                    <p class="text-xs text-gray-400 mt-1">Between 10 and 500 characters.</p>
                </div>
                <button type="submit"
                        class="w-full py-3 rounded-lg font-bold transition flex items-center justify-center gap-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white hover:from-indigo-700 hover:to-purple-700">
                    <span class="text-lg">🙏</span>
                    <span>Submit Prayer Request</span>
                </button>
            </form>
