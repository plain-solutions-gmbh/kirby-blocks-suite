<template>
  <div
    :data-disabled="disabled"
    :data-empty="blocks.length === 0"
    class="k-blocks"
  >
    <template v-if="hasFieldsets">
      <!-- add class width preview -->
      <k-draggable
        v-bind="draggableOptions"
        :data-multi-select-key="isMultiSelectKey"
        class="k-blocks-list"
        @sort="save"
      >
        <!-- add ':style' -->
        <k-block
          v-for="(block, index) in blocks"
          :ref="'block-' + block.id"
          :key="block.id"
          :style="'width:' + getWidth(block)"
          v-bind="{
            ...block,
            disabled,
            endpoints,
            fieldset: fieldset(block),
            isBatched: isSelected(block) && selected.length > 1,
            isFull,
            isHidden: block.isHidden === true,
            isLastSelected: isLastSelected(block),
            isMergable,
            isSelected: isSelected(block),
            next: prevNext(index + 1),
            prev: prevNext(index - 1),
          }"
          @append="add($event, index + 1)"
          @chooseToAppend="choose(index + 1)"
          @chooseToConvert="chooseToConvert(block)"
          @chooseToPrepend="choose(index)"
          @click.native="onClickBlock(block, $event)"
          @close="isEditing = false"
          @copy="copy()"
          @duplicate="duplicate(block, index)"
          @focus="onFocus(block)"
          @hide="hide(block)"
          @merge="merge()"
          @open="isEditing = true"
          @paste="pasteboard()"
          @prepend="add($event, index)"
          @remove="remove(block)"
          @removeSelected="removeSelected"
          @show="show(block)"
          @selectDown="selectDown"
          @selectUp="selectUp"
          @sortDown="sort(block, index, index + 1)"
          @sortUp="sort(block, index, index - 1)"
          @split="split(block, index, $event)"
          @update="update(block, $event)"
        />

        <!-- No blocks -->
        <template v-if="blocks.length === 0" #footer>
          <k-empty
            class="k-blocks-empty"
            icon="box"
            @click="choose(blocks.length)"
          >
            {{ empty ?? $t("field.blocks.empty") }}
          </k-empty>
        </template>
      </k-draggable>
    </template>

    <!-- No fieldsets -->
    <k-empty v-else icon="box">
      {{ $t("field.blocks.fieldsets.empty") }}
    </k-empty>
    <div class="bs-alpha"><strong>Blocks Suite</strong> is alpha.<br>Use at your own risk.</div>
  </div>
</template>

<script>
export default {
  // Fix confusion $id <-> $type
  // Now we can use same blocke multiple times
  extends: "k-blocks",
  inheritAttrs: false,
  methods: {
    // Only symantic
    async add(fieldset = "text", index) {
      const block = await this.$api.get(
        this.endpoints.field + "/fieldsets/" + fieldset
      );
      this.blocks.splice(index, 0, block);

      this.save();

      await this.$nextTick();
      this.focusOrOpen(block);
    },
    choose(index) {
      if (this.$helper.object.length(this.fieldsets) === 1) {
        return this.add(Object.values(this.fieldsets)[0].id, index);
      }

      this.$panel.dialog.open({
        component: "k-block-selector",
        props: {
          fieldsetGroups: this.fieldsetGroups,
          fieldsets: this.fieldsets,
        },
        on: {
          // Symantic
          submit: (fieldset) => {
            this.add(fieldset, index);
            this.$panel.dialog.close();
          },
          paste: (e) => {
            this.paste(e, index);
          },
        },
      });
    },
    getWidth(block) {
      const preview = this.fieldset(block).previewObj?.width ?? {};
      let value = block.content[preview?.field] ?? preview?.default ?? null;
      let format = preview.format;

      if (value === null) {
        return "100%;";
      }

      if (format === "options" && preview?.options) {
        return preview?.options[value] ?? preview.default;
      }

      if (format === "columns") {
        return 100 / value + "%";
      }

      if (Number.isInteger(format)) {
        return (100 * value) / format + "%";
      }

      if (format === "fraction") {
        const split = value.split("/");
        value = parseInt(split[0]);
        format = parseInt(split[1]);
      }

      return value + format;
    },
    chooseToConvert(block) {
      this.$panel.dialog.open({
        component: "k-block-selector",
        props: {
          // fieldset instead of type
          disabledFieldsets: [block.fieldset],
          fieldsetGroups: this.fieldsetGroups,
          fieldsets: this.fieldsets,
          headline: this.$t("field.blocks.changeType"),
        },
        on: {
          // Symantic
          submit: (fieldset) => {
            this.convert(fieldset, block);
            this.$panel.dialog.close();
          },
          paste: this.paste,
        },
      });
    },
    async convert(fieldset, block) {
      const index = this.findIndex(block.id);

      if (index === -1) {
        return false;
      }

      const fields = (fieldset) => {
        let fields = {};

        for (const tab of Object.values(fieldset?.tabs ?? {})) {
          fields = {
            ...fields,
            ...tab.fields,
          };
        }

        return fields;
      };

      const oldBlock = this.blocks[index];
      const newBlock = await this.$api.get(
        this.endpoints.field + "/fieldsets/" + fieldset
      );

      const oldFieldset = this.fieldsets[oldBlock.fieldset];
      const newFieldset = this.fieldsets[fieldset];

      if (!newFieldset) {
        return false;
      }

      let content = newBlock.content;

      const newFields = fields(newFieldset);
      const oldFields = fields(oldFieldset);

      for (const [name, field] of Object.entries(newFields)) {
        const oldField = oldFields[name];

        if (oldField?.type === field.type && oldBlock?.content?.[name]) {
          content[name] = oldBlock.content[name];
        }
      }

      this.blocks[index] = {
        ...newBlock,
        id: oldBlock.id,
        fieldset: fieldset,
        content: content,
      };

      this.save();
    },
    // fieldset instead of type
    fieldset(block) {
      return (
        this.fieldsets[block.fieldset] ?? {
          icon: "box",
          name: block.fieldset,
          fieldset: block.fieldset,
          tabs: {
            content: {
              fields: {},
            },
          },
          type: block.type,
        }
      );
    },
    // fieldset instead of type
    focusOrOpen(block) {
      if (this.fieldsets[block.fieldset].wysiwyg) {
        this.focus(block);
      } else {
        this.open(block);
      }
    },
    // fieldset instead of type
    move(event) {
      if (event.from !== event.to) {
        const block = event.draggedContext.element;
        const to =
          event.relatedContext.component.componentData ||
          event.relatedContext.component.$parent.componentData;

        if (Object.keys(to.fieldsets).includes(block.fieldset) === false) {
          return false;
        }

        if (to.isFull === true) {
          return false;
        }
      }

      return true;
    },

    save() {
      this.$emit("input", this.blocks);
    },
  },
};
</script>

<style>
.k-blocks-list > .k-block-container {
  width: 100%;
  border: 1px solid var(--color-gray-200);
}

.k-blocks-list {
  display: flex;
  flex-wrap: wrap;
}

.bs-alpha {
  position: absolute;
  right: 0;
  padding: 0.7em 0;
  text-align: right;
  font-size: 0.8em;
  color: var(--color-gray-500);
}
</style>
