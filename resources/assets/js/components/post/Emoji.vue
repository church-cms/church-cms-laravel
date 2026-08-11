<template>
    <div>
        <div v-if="this.success != null" class="alert alert-success" id="success-alert">{{ this.success }}</div>
        <div class="textarea-emoji-picker">
            <textarea ref="textarea" class="tw-form-control w-full" :value="value" @input="$emit('input', $event.target.value)" rows="1"></textarea>
            <picker v-show="showEmojiPicker" title="Pick your emoji..." emoji="point_up" @select="addEmoji"></picker>
            <span class="emoji-trigger" :class="{ 'triggered': showEmojiPicker }" @mousedown.prevent="toggleEmojiPicker">
                <svg class="w-4 h-4 fill-current text-gray-600 mt-1" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve"><g><g><path d="M437.02,74.98C388.667,26.629,324.38,0,256,0S123.333,26.629,74.98,74.98C26.629,123.333,0,187.62,0,256 s26.629,132.668,74.98,181.02C123.333,485.371,187.62,512,256,512s132.667-26.629,181.02-74.98 C485.371,388.668,512,324.38,512,256S485.371,123.333,437.02,74.98z M256,472c-119.103,0-216-96.897-216-216S136.897,40,256,40 s216,96.897,216,216S375.103,472,256,472z"/></g></g><g><g><path d="M368.993,285.776c-0.072,0.214-7.298,21.626-25.02,42.393C321.419,354.599,292.628,368,258.4,368 c-34.475,0-64.195-13.561-88.333-40.303c-18.92-20.962-27.272-42.54-27.33-42.691l-37.475,13.99 c0.42,1.122,10.533,27.792,34.013,54.273C171.022,389.074,212.215,408,258.4,408c46.412,0,86.904-19.076,117.099-55.166 c22.318-26.675,31.165-53.55,31.531-54.681L368.993,285.776z"/></g></g><g><g><circle cx="168" cy="180.12" r="32"/></g></g><g><g><circle cx="344" cy="180.12" r="32"/></g></g></svg>
            </span>
        </div>
    </div>
</template>

<script>
    import { Picker } from 'emoji-mart-vue'
    export default {
        components: { Picker },
        props: {
            value: {
                type: String,
                default: ''
            }
        },
        data(){
            return{
                showEmojiPicker: false,
                success:null,
                errors:[],      
            }
        },

        methods: 
        {
            toggleEmojiPicker () 
            {
                this.showEmojiPicker = !this.showEmojiPicker
            },

            addEmoji (emoji) 
            {
                const textarea = this.$refs.textarea
                const cursorPosition = textarea.selectionEnd
                const start = this.value.substring(0, textarea.selectionStart)
                const end = this.value.substring(textarea.selectionStart)
                const text = start + emoji.native + end
                this.$emit('input', text)
                textarea.focus()
                this.$nextTick(() => {
                  textarea.selectionEnd = cursorPosition + emoji.native.length
                })
            },
        },
    }
</script>

<style scoped>
    * {
        box-sizing: border-box;
    }

    .textarea-emoji-picker {
        position: relative;
        width: 395px;
        margin: 0 auto;
    }

    .textarea {
        width: 100%;
        outline: none;
        box-shadow: none;
        padding: 10px 28px 10px 10px;
        font-size: 15px;
        border: 1px solid #BABABA;
        color: #333;
        border-radius: 2px;
        box-shadow: 0px 2px 4px 0 rgba(0,0,0,0.1) inset;
        resize: vertical;
    }

    .emoji-mart {
        /*position: absolute;*/
        position: sticky;
        top: 33px;
        right: 10px;
    }

    .emoji-trigger {
        position: absolute;
        top: 6px;
        right: 10px;
        cursor: pointer;
        height: 20px;
    }

    .emoji-trigger path {
        transition: 0.1s all;
    }

    .emoji-trigger:hover path {
        fill: #000000;
    }

    .emoji-trigger.triggered path {
        fill: darken(#FEC84A, 15%);
    }
</style>