<template>
  <div class="tiptap-editor">
    <div class="tiptap-toolbar">
      <!-- Text Formatting -->
      <button type="button" :class="{ 'is-active': editor && editor.isActive('bold') }" @click="editor.chain().focus().toggleBold().run()" title="Bold">
        <strong>B</strong>
      </button>
      <button type="button" :class="{ 'is-active': editor && editor.isActive('italic') }" @click="editor.chain().focus().toggleItalic().run()" title="Italic">
        <em>I</em>
      </button>
      <button type="button" :class="{ 'is-active': editor && editor.isActive('underline') }" @click="editor.chain().focus().toggleUnderline().run()" title="Underline">
        <u>U</u>
      </button>

      <span class="tiptap-divider"></span>

      <!-- Headings -->
      <button type="button" :class="{ 'is-active': editor && editor.isActive('heading', { level: 1 }) }" @click="editor.chain().focus().toggleHeading({ level: 1 }).run()" title="Heading 1">H1</button>
      <button type="button" :class="{ 'is-active': editor && editor.isActive('heading', { level: 2 }) }" @click="editor.chain().focus().toggleHeading({ level: 2 }).run()" title="Heading 2">H2</button>
      <button type="button" :class="{ 'is-active': editor && editor.isActive('heading', { level: 3 }) }" @click="editor.chain().focus().toggleHeading({ level: 3 }).run()" title="Heading 3">H3</button>
      <button type="button" :class="{ 'is-active': editor && editor.isActive('heading', { level: 4 }) }" @click="editor.chain().focus().toggleHeading({ level: 4 }).run()" title="Heading 4">H4</button>

      <span class="tiptap-divider"></span>

      <!-- Lists -->
      <button type="button" :class="{ 'is-active': editor && editor.isActive('bulletList') }" @click="editor.chain().focus().toggleBulletList().run()" title="Bullet List">&#8226; List</button>
      <button type="button" :class="{ 'is-active': editor && editor.isActive('orderedList') }" @click="editor.chain().focus().toggleOrderedList().run()" title="Ordered List">1. List</button>

      <span class="tiptap-divider"></span>

      <!-- Blocks -->
      <button type="button" :class="{ 'is-active': editor && editor.isActive('blockquote') }" @click="editor.chain().focus().toggleBlockquote().run()" title="Blockquote">" Quote</button>
      <button type="button" :class="{ 'is-active': editor && editor.isActive('codeBlock') }" @click="editor.chain().focus().toggleCodeBlock().run()" title="Code Block">&lt;/&gt; Code</button>

      <span class="tiptap-divider"></span>

      <!-- Link -->
      <button type="button" :class="{ 'is-active': editor && editor.isActive('link') }" @click="setLink" title="Link">&#128279; Link</button>
      <button type="button" v-if="editor && editor.isActive('link')" @click="editor.chain().focus().unsetLink().run()" title="Remove Link">&#10006; Link</button>

      <!-- Image -->
      <button type="button" @click="addImage" title="Image">&#128444; Image</button>

      <span class="tiptap-divider"></span>

      <!-- History -->
      <button type="button" @click="editor.chain().focus().undo().run()" :disabled="editor && !editor.can().undo()" title="Undo">&#8630; Undo</button>
      <button type="button" @click="editor.chain().focus().redo().run()" :disabled="editor && !editor.can().redo()" title="Redo">&#8631; Redo</button>
    </div>
    <editor-content :editor="editor" class="tiptap-content" />
  </div>
</template>

<script>
import { Editor, EditorContent } from '@tiptap/vue-2'
import StarterKit from '@tiptap/starter-kit'
import Underline from '@tiptap/extension-underline'
import Link from '@tiptap/extension-link'
import Image from '@tiptap/extension-image'

export default {
  name: 'TiptapEditor',
  components: { EditorContent },

  props: {
    value: {
      type: [Object, String],
      default: null,
    },
  },

  data() {
    return {
      editor: null,
    }
  },

  mounted() {
    this.editor = new Editor({
      extensions: [
        StarterKit,
        Underline,
        Link.configure({ openOnClick: false }),
        Image,
      ],
      content: this.value || '',
      onUpdate: () => {
        this.$emit('input', this.editor.getJSON())
        this.$emit('update:html', this.editor.getHTML())
      },
    })
  },

  beforeDestroy() {
    if (this.editor) {
      this.editor.destroy()
    }
  },

  watch: {
    value(newVal) {
      if (!this.editor) return
      const currentJSON = JSON.stringify(this.editor.getJSON())
      const newJSON = typeof newVal === 'string' ? newVal : JSON.stringify(newVal)
      if (currentJSON === newJSON) return
      this.editor.commands.setContent(newVal || '', false)
    },
  },

  methods: {
    setLink() {
      const previousUrl = this.editor.getAttributes('link').href
      const url = window.prompt('URL', previousUrl)
      if (url === null) return
      if (url === '') {
        this.editor.chain().focus().extendMarkRange('link').unsetLink().run()
        return
      }
      this.editor.chain().focus().extendMarkRange('link').setLink({ href: url }).run()
    },

    addImage() {
      const url = window.prompt('Image URL')
      if (url) {
        this.editor.chain().focus().setImage({ src: url }).run()
      }
    },
  },
}
</script>

<style>
.tiptap-editor {
  border: 1px solid #d1d5db;
  border-radius: 6px;
  overflow: hidden;
}

.tiptap-toolbar {
  display: flex;
  flex-wrap: wrap;
  gap: 2px;
  padding: 6px 8px;
  background: #f9fafb;
  border-bottom: 1px solid #d1d5db;
}

.tiptap-toolbar button {
  padding: 4px 8px;
  font-size: 13px;
  border: 1px solid transparent;
  border-radius: 4px;
  background: transparent;
  cursor: pointer;
  color: #374151;
  line-height: 1.4;
}

.tiptap-toolbar button:hover {
  background: #e5e7eb;
  border-color: #d1d5db;
}

.tiptap-toolbar button.is-active {
  background: #3b82f6;
  color: #fff;
  border-color: #2563eb;
}

.tiptap-toolbar button:disabled {
  opacity: 0.4;
  cursor: not-allowed;
}

.tiptap-divider {
  display: inline-block;
  width: 1px;
  background: #d1d5db;
  margin: 2px 4px;
  align-self: stretch;
}

.tiptap-content {
  padding: 12px 16px;
  min-height: 320px;
  outline: none;
}

.tiptap-content .ProseMirror {
  min-height: 300px;
  outline: none;
}

.tiptap-content .ProseMirror h1 { font-size: 1.875rem; font-weight: 700; margin: 1rem 0 0.5rem; }
.tiptap-content .ProseMirror h2 { font-size: 1.5rem; font-weight: 700; margin: 1rem 0 0.5rem; }
.tiptap-content .ProseMirror h3 { font-size: 1.25rem; font-weight: 600; margin: 0.75rem 0 0.4rem; }
.tiptap-content .ProseMirror h4 { font-size: 1.125rem; font-weight: 600; margin: 0.75rem 0 0.4rem; }
.tiptap-content .ProseMirror p { margin: 0.5rem 0; }
.tiptap-content .ProseMirror ul { list-style-type: disc; padding-left: 1.5rem; margin: 0.5rem 0; }
.tiptap-content .ProseMirror ol { list-style-type: decimal; padding-left: 1.5rem; margin: 0.5rem 0; }
.tiptap-content .ProseMirror blockquote { border-left: 4px solid #d1d5db; padding-left: 1rem; color: #6b7280; margin: 0.75rem 0; }
.tiptap-content .ProseMirror code { background: #f3f4f6; border-radius: 3px; padding: 0.1em 0.3em; font-family: monospace; }
.tiptap-content .ProseMirror pre { background: #1f2937; color: #f9fafb; padding: 0.75rem 1rem; border-radius: 6px; overflow-x: auto; margin: 0.75rem 0; }
.tiptap-content .ProseMirror pre code { background: none; padding: 0; color: inherit; }
.tiptap-content .ProseMirror a { color: #3b82f6; text-decoration: underline; }
.tiptap-content .ProseMirror img { max-width: 100%; height: auto; border-radius: 4px; }
.tiptap-content .ProseMirror p.is-editor-empty:first-child::before {
  content: attr(data-placeholder);
  float: left;
  color: #adb5bd;
  pointer-events: none;
  height: 0;
}
</style>
