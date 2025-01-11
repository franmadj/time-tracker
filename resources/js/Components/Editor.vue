<template>
    <ckeditor
        v-if="editor"
        v-model="data"
        :editor="editor"
        :config="config"
        @ready="onReady"
    />
</template>

<script setup>
import { ref, computed } from 'vue';
import { Ckeditor, useCKEditorCloud } from '@ckeditor/ckeditor5-vue';

const cloud = useCKEditorCloud( {
    version: '44.1.0'
} );

const data = ref( '<p>Hello world!</p>' );

const editor = computed( () => {
    if ( !cloud.data.value ) {
        return null;
    }

    return cloud.data.value.CKEditor.ClassicEditor;
} );

const config = computed( () => {
    if ( !cloud.data.value ) {
        return null;
    }

    const { Essentials, Paragraph, Bold, Italic, Mention } = cloud.data.value.CKEditor;
    const { SlashCommand } = cloud.data.value.CKEditorPremiumFeatures;

    return {
        licenseKey: 'GPL',
        toolbar: [ 'undo', 'redo', '|', 'bold', 'italic' ],
        plugins: [
            Essentials,
            Paragraph,
            Bold,
            Italic,
            Mention,
            SlashCommand
        ]
    };
} );

function onReady( editor ) {
    // Insert the toolbar before the editable area.
    editor.ui.getEditableElement().parentElement.insertBefore(
        editor.ui.view.toolbar.element,
        editor.ui.getEditableElement()
    );
}
</script>
