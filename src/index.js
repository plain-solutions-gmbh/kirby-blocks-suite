import BlockField from "./Fields/BlockField.vue";
import BlockTypeFields from "./Components/FieldsPreview.vue";
import BlockSelectorComponent from "./Components/BlockSelectorComponent.vue";

import Block from "./Components/BlockComponent.vue";
import Blocks from "./Components/BlocksComponent.vue";
import BlockTitle from "./Components/BlockTitle.vue";
import BlockForm from "./Components/FormComponent.vue";
import BlockTabs from "./Components/TabsComponent.vue";

window.panel.plugin("plain/blocks-suite", {
    fields: {
        block: BlockField,
    },
    components: {
        "k-block-selector": BlockSelectorComponent,
        "k-block-title": BlockTitle,
        "k-block-type-fields": BlockTypeFields,
        "k-block": Block,
        "k-blocks": Blocks,
        "k-block-form": BlockForm,
        "k-block-tabs": BlockTabs,
    },
});