(function() {
  "use strict";
  const BlockField_vue_vue_type_style_index_0_lang = "";
  function normalizeComponent(scriptExports, render, staticRenderFns, functionalTemplate, injectStyles, scopeId, moduleIdentifier, shadowMode) {
    var options = typeof scriptExports === "function" ? scriptExports.options : scriptExports;
    if (render) {
      options.render = render;
      options.staticRenderFns = staticRenderFns;
      options._compiled = true;
    }
    if (functionalTemplate) {
      options.functional = true;
    }
    if (scopeId) {
      options._scopeId = "data-v-" + scopeId;
    }
    var hook;
    if (moduleIdentifier) {
      hook = function(context) {
        context = context || this.$vnode && this.$vnode.ssrContext || this.parent && this.parent.$vnode && this.parent.$vnode.ssrContext;
        if (!context && typeof __VUE_SSR_CONTEXT__ !== "undefined") {
          context = __VUE_SSR_CONTEXT__;
        }
        if (injectStyles) {
          injectStyles.call(this, context);
        }
        if (context && context._registeredComponents) {
          context._registeredComponents.add(moduleIdentifier);
        }
      };
      options._ssrRegister = hook;
    } else if (injectStyles) {
      hook = shadowMode ? function() {
        injectStyles.call(
          this,
          (options.functional ? this.parent : this).$root.$options.shadowRoot
        );
      } : injectStyles;
    }
    if (hook) {
      if (options.functional) {
        options._injectStyles = hook;
        var originalRender = options.render;
        options.render = function renderWithStyleInjection(h, context) {
          hook.call(context);
          return originalRender(h, context);
        };
      } else {
        var existing = options.beforeCreate;
        options.beforeCreate = existing ? [].concat(existing, hook) : [hook];
      }
    }
    return {
      exports: scriptExports,
      options
    };
  }
  const _sfc_main$7 = {
    extends: "k-field",
    inheritAttrs: false,
    props: {
      tabs: {
        type: Object,
        default() {
        }
      },
      value: Object
    },
    data() {
      return {
        currentTab: null,
        parsedTabs: []
      };
    },
    created() {
      var _a, _b;
      for (const [tabname, tab] of Object.entries(this.tabs)) {
        (_a = tab.name) != null ? _a : tab.name = tabname;
        Object.entries(tab.fields).forEach(([fieldName]) => {
          tab.fields[fieldName].section = this.name;
          tab.fields[fieldName].endpoints = {
            field: this.endpoints.field + "/fields/" + fieldName,
            section: this.endpoints.section,
            model: this.endpoints.model
          };
        });
        this.parsedTabs.push(tab);
      }
      let availableTabs = Object.keys(this.tabs);
      this.currentTab = (_b = sessionStorage.getItem(`plain.blocks.tab.${this.hash}`)) != null ? _b : "content";
      if (availableTabs.includes(this.currentTab) === false) {
        this.currentTab = availableTabs[0];
      }
    },
    methods: {
      setTab(tab) {
        this.currentTab = tab;
        sessionStorage.setItem(`plain.blocks.tab.${this.hash}`, tab);
      },
      setInvalid(validation, index) {
        this.parsedTabs[index].badge = "";
        Object.values(validation).forEach((field) => {
          if (field.$invalid) {
            this.parsedTabs[index].badge = "\xD7";
          }
        });
      }
    }
  };
  var _sfc_render$7 = function render() {
    var _vm = this, _c = _vm._self._c;
    return _c("k-field", _vm._b({ staticClass: "k-block-field" }, "k-field", _vm.$props, false), [_c("k-block-tabs", { ref: "tabElement", attrs: { "tab": _vm.currentTab, "tabs": _vm.parsedTabs }, on: { "update": function($event) {
      return _vm.setTab($event);
    } } }), _vm._l(_vm.parsedTabs, function(tab, index) {
      return _c("k-form", _vm._b({ key: index, ref: "editor", refInFor: true, staticClass: "k-block-form", attrs: { "value": _vm.value, "hidden": tab.name !== _vm.currentTab }, on: { "input": function($event) {
        return _vm.$emit("input", $event);
      }, "invalid": function($event) {
        return _vm.setInvalid($event, index);
      } } }, "k-form", tab, false));
    })], 2);
  };
  var _sfc_staticRenderFns$7 = [];
  _sfc_render$7._withStripped = true;
  var __component__$7 = /* @__PURE__ */ normalizeComponent(
    _sfc_main$7,
    _sfc_render$7,
    _sfc_staticRenderFns$7,
    false,
    null,
    null,
    null,
    null
  );
  __component__$7.options.__file = "/Users/romangsponer/Cloud/_sites/plugin-env/site/plugins/kirby-blocks-suite/src/Fields/BlockField.vue";
  const BlockField = __component__$7.exports;
  const FieldsPreview_vue_vue_type_style_index_0_lang = "";
  const _sfc_main$6 = {
    extends: "k-block-type-fields",
    props: {
      fieldset: Object,
      endpoints: Object,
      type: String
    },
    computed: {
      preview() {
        return this.fieldset.previewObj;
      },
      fields() {
        var _a, _b;
        const fields = (_a = this.preview.fields) != null ? _a : {};
        for (const [fieldName] of Object.entries(fields)) {
          fields[fieldName].section = this.name;
          fields[fieldName].endpoints = {
            field: this.endpoints.field + "/fieldsets/" + ((_b = this.name) != null ? _b : this.type.replace("/", "__")) + "/fields/" + fieldName,
            section: this.endpoints.section,
            model: this.endpoints.model
          };
        }
        return fields;
      }
    }
  };
  var _sfc_render$6 = function render() {
    var _vm = this, _c = _vm._self._c;
    return _c("div", { attrs: { "data-collapsed": _vm.collapsed }, on: { "dblclick": function($event) {
      return _vm.$emit("open");
    } } }, [_c("header", { staticClass: "k-block-type-fields-header" }, [_c("k-block-title", { attrs: { "content": _vm.values, "fieldset": _vm.fieldset }, nativeOn: { "click": function($event) {
      return _vm.toggle.apply(null, arguments);
    } } }), _c("k-icon", { style: "transform: rotate(" + (_vm.collapsed ? 0 : "-180deg") + ")", attrs: { "type": "angle-down" }, nativeOn: { "click": function($event) {
      return _vm.toggle.apply(null, arguments);
    } } })], 1), !_vm.collapsed ? _c("k-form", { ref: "form", staticClass: "k-block-type-fields-form", attrs: { "autofocus": true, "disabled": _vm.disabled, "fields": _vm.fields, "endpoints": _vm.endpoints, "value": _vm.values }, on: { "input": function($event) {
      return _vm.$emit("update", $event);
    } } }) : _vm._e()], 1);
  };
  var _sfc_staticRenderFns$6 = [];
  _sfc_render$6._withStripped = true;
  var __component__$6 = /* @__PURE__ */ normalizeComponent(
    _sfc_main$6,
    _sfc_render$6,
    _sfc_staticRenderFns$6,
    false,
    null,
    null,
    null,
    null
  );
  __component__$6.options.__file = "/Users/romangsponer/Cloud/_sites/plugin-env/site/plugins/kirby-blocks-suite/src/Components/FieldsPreview.vue";
  const BlockTypeFields = __component__$6.exports;
  const _sfc_main$5 = {
    extends: "k-block-selector",
    inheritAttrs: false
  };
  var _sfc_render$5 = function render() {
    var _vm = this, _c = _vm._self._c;
    return _c("k-dialog", { staticClass: "k-block-selector", attrs: { "cancel-button": false, "size": _vm.size, "submit-button": false, "visible": true }, on: { "cancel": function($event) {
      return _vm.$emit("cancel");
    }, "submit": function($event) {
      return _vm.$emit("submit", _vm.value);
    } } }, [_vm.headline ? _c("k-headline", [_vm._v(" " + _vm._s(_vm.headline) + " ")]) : _vm._e(), _vm._l(_vm.groups, function(group, groupName) {
      return _c("details", { key: groupName, attrs: { "open": group.open } }, [_c("summary", [_vm._v(_vm._s(group.label))]), _c("k-navigate", { staticClass: "k-block-types" }, _vm._l(group.fieldsets, function(fieldset) {
        var _a;
        return _c("k-button", { key: fieldset.name, attrs: { "disabled": _vm.disabledFieldsets.includes(fieldset.id), "icon": (_a = fieldset.icon) != null ? _a : "box", "text": fieldset.name, "size": "lg" }, on: { "click": function($event) {
          return _vm.$emit("submit", fieldset.id);
        } }, nativeOn: { "focus": function($event) {
          return _vm.$emit("input", fieldset.id);
        } } });
      }), 1)], 1);
    }), _c("p", { staticClass: "k-clipboard-hint", domProps: { "innerHTML": _vm._s(_vm.$t("field.blocks.fieldsets.paste", { shortcut: _vm.shortcut })) } })], 2);
  };
  var _sfc_staticRenderFns$5 = [];
  _sfc_render$5._withStripped = true;
  var __component__$5 = /* @__PURE__ */ normalizeComponent(
    _sfc_main$5,
    _sfc_render$5,
    _sfc_staticRenderFns$5,
    false,
    null,
    null,
    null,
    null
  );
  __component__$5.options.__file = "/Users/romangsponer/Cloud/_sites/plugin-env/site/plugins/kirby-blocks-suite/src/Components/BlockSelectorComponent.vue";
  const BlockSelectorComponent = __component__$5.exports;
  const _sfc_main$4 = {
    extends: "k-block",
    inheritAttrs: false,
    computed: {
      tabs() {
        var _a, _b, _c;
        const tabs = (_a = this.fieldset.tabs) != null ? _a : {};
        const previewFields = Object.keys(this.fieldset.previewObj.fields);
        for (const [tabName, tab] of Object.entries(tabs)) {
          for (const [fieldName] of Object.entries((_b = tab.fields) != null ? _b : {})) {
            if (previewFields.includes(fieldName)) {
              delete tabs[tabName].fields[fieldName];
              continue;
            }
            tabs[tabName].fields[fieldName].section = this.name;
            tabs[tabName].fields[fieldName].endpoints = {
              field: this.endpoints.field + "/fieldsets/" + ((_c = this.name) != null ? _c : this.type.replace("/", "__")) + "/fields/" + fieldName,
              section: this.endpoints.section,
              model: this.endpoints.model
            };
          }
          if (Object.keys(tabs[tabName].fields).length === 0 || this.fieldset.previewObj.tab === tabName) {
            delete tabs[tabName];
          }
        }
        return tabs;
      }
    }
  };
  const _sfc_render$4 = null;
  const _sfc_staticRenderFns$4 = null;
  var __component__$4 = /* @__PURE__ */ normalizeComponent(
    _sfc_main$4,
    _sfc_render$4,
    _sfc_staticRenderFns$4,
    false,
    null,
    null,
    null,
    null
  );
  __component__$4.options.__file = "/Users/romangsponer/Cloud/_sites/plugin-env/site/plugins/kirby-blocks-suite/src/Components/BlockComponent.vue";
  const Block = __component__$4.exports;
  const BlocksComponent_vue_vue_type_style_index_0_lang = "";
  const _sfc_main$3 = {
    extends: "k-blocks",
    inheritAttrs: false,
    methods: {
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
            fieldsets: this.fieldsets
          },
          on: {
            submit: (fieldset) => {
              this.add(fieldset, index);
              this.$panel.dialog.close();
            },
            paste: (e) => {
              this.paste(e, index);
            }
          }
        });
      },
      getWidth(block) {
        var _a, _b, _c, _d, _e;
        const preview = (_b = (_a = this.fieldset(block).previewObj) == null ? void 0 : _a.width) != null ? _b : {};
        let value = (_d = (_c = block.content[preview == null ? void 0 : preview.field]) != null ? _c : preview == null ? void 0 : preview.default) != null ? _d : null;
        let format = preview.format;
        if (value === null) {
          return "100%;";
        }
        if (format === "options" && (preview == null ? void 0 : preview.options)) {
          return (_e = preview == null ? void 0 : preview.options[value]) != null ? _e : preview.default;
        }
        if (format === "columns") {
          return 100 / value + "%";
        }
        if (Number.isInteger(format)) {
          return 100 * value / format + "%";
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
            disabledFieldsets: [block.fieldset],
            fieldsetGroups: this.fieldsetGroups,
            fieldsets: this.fieldsets,
            headline: this.$t("field.blocks.changeType")
          },
          on: {
            submit: (fieldset) => {
              this.convert(fieldset, block);
              this.$panel.dialog.close();
            },
            paste: this.paste
          }
        });
      },
      async convert(fieldset, block) {
        var _a;
        const index = this.findIndex(block.id);
        if (index === -1) {
          return false;
        }
        const fields = (fieldset2) => {
          var _a2;
          let fields2 = {};
          for (const tab of Object.values((_a2 = fieldset2 == null ? void 0 : fieldset2.tabs) != null ? _a2 : {})) {
            fields2 = {
              ...fields2,
              ...tab.fields
            };
          }
          return fields2;
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
          if ((oldField == null ? void 0 : oldField.type) === field.type && ((_a = oldBlock == null ? void 0 : oldBlock.content) == null ? void 0 : _a[name])) {
            content[name] = oldBlock.content[name];
          }
        }
        this.blocks[index] = {
          ...newBlock,
          id: oldBlock.id,
          fieldset,
          content
        };
        this.save();
      },
      fieldset(block) {
        var _a;
        return (_a = this.fieldsets[block.fieldset]) != null ? _a : {
          icon: "box",
          name: block.fieldset,
          fieldset: block.fieldset,
          tabs: {
            content: {
              fields: {}
            }
          },
          type: block.type
        };
      },
      focusOrOpen(block) {
        if (this.fieldsets[block.fieldset].wysiwyg) {
          this.focus(block);
        } else {
          this.open(block);
        }
      },
      move(event) {
        if (event.from !== event.to) {
          const block = event.draggedContext.element;
          const to = event.relatedContext.component.componentData || event.relatedContext.component.$parent.componentData;
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
      }
    }
  };
  var _sfc_render$3 = function render() {
    var _vm = this, _c = _vm._self._c;
    return _c("div", { staticClass: "k-blocks", attrs: { "data-disabled": _vm.disabled, "data-empty": _vm.blocks.length === 0 } }, [_vm.hasFieldsets ? [_c("k-draggable", _vm._b({ staticClass: "k-blocks-list", attrs: { "data-multi-select-key": _vm.isMultiSelectKey }, on: { "sort": _vm.save }, scopedSlots: _vm._u([_vm.blocks.length === 0 ? { key: "footer", fn: function() {
      var _a;
      return [_c("k-empty", { staticClass: "k-blocks-empty", attrs: { "icon": "box" }, on: { "click": function($event) {
        return _vm.choose(_vm.blocks.length);
      } } }, [_vm._v(" " + _vm._s((_a = _vm.empty) != null ? _a : _vm.$t("field.blocks.empty")) + " ")])];
    }, proxy: true } : null], null, true) }, "k-draggable", _vm.draggableOptions, false), _vm._l(_vm.blocks, function(block, index) {
      return _c("k-block", _vm._b({ key: block.id, ref: "block-" + block.id, refInFor: true, style: "width:" + _vm.getWidth(block), on: { "append": function($event) {
        return _vm.add($event, index + 1);
      }, "chooseToAppend": function($event) {
        return _vm.choose(index + 1);
      }, "chooseToConvert": function($event) {
        return _vm.chooseToConvert(block);
      }, "chooseToPrepend": function($event) {
        return _vm.choose(index);
      }, "close": function($event) {
        _vm.isEditing = false;
      }, "copy": function($event) {
        return _vm.copy();
      }, "duplicate": function($event) {
        return _vm.duplicate(block, index);
      }, "focus": function($event) {
        return _vm.onFocus(block);
      }, "hide": function($event) {
        return _vm.hide(block);
      }, "merge": function($event) {
        return _vm.merge();
      }, "open": function($event) {
        _vm.isEditing = true;
      }, "paste": function($event) {
        return _vm.pasteboard();
      }, "prepend": function($event) {
        return _vm.add($event, index);
      }, "remove": function($event) {
        return _vm.remove(block);
      }, "removeSelected": _vm.removeSelected, "show": function($event) {
        return _vm.show(block);
      }, "selectDown": _vm.selectDown, "selectUp": _vm.selectUp, "sortDown": function($event) {
        return _vm.sort(block, index, index + 1);
      }, "sortUp": function($event) {
        return _vm.sort(block, index, index - 1);
      }, "split": function($event) {
        return _vm.split(block, index, $event);
      }, "update": function($event) {
        return _vm.update(block, $event);
      } }, nativeOn: { "click": function($event) {
        return _vm.onClickBlock(block, $event);
      } } }, "k-block", {
        ...block,
        disabled: _vm.disabled,
        endpoints: _vm.endpoints,
        fieldset: _vm.fieldset(block),
        isBatched: _vm.isSelected(block) && _vm.selected.length > 1,
        isFull: _vm.isFull,
        isHidden: block.isHidden === true,
        isLastSelected: _vm.isLastSelected(block),
        isMergable: _vm.isMergable,
        isSelected: _vm.isSelected(block),
        next: _vm.prevNext(index + 1),
        prev: _vm.prevNext(index - 1)
      }, false));
    }), 1)] : _c("k-empty", { attrs: { "icon": "box" } }, [_vm._v(" " + _vm._s(_vm.$t("field.blocks.fieldsets.empty")) + " ")]), _vm._m(0)], 2);
  };
  var _sfc_staticRenderFns$3 = [function() {
    var _vm = this, _c = _vm._self._c;
    return _c("div", { staticClass: "bs-alpha" }, [_c("strong", [_vm._v("Blocks Suite")]), _vm._v(" is alpha."), _c("br"), _vm._v("Use at your own risk.")]);
  }];
  _sfc_render$3._withStripped = true;
  var __component__$3 = /* @__PURE__ */ normalizeComponent(
    _sfc_main$3,
    _sfc_render$3,
    _sfc_staticRenderFns$3,
    false,
    null,
    null,
    null,
    null
  );
  __component__$3.options.__file = "/Users/romangsponer/Cloud/_sites/plugin-env/site/plugins/kirby-blocks-suite/src/Components/BlocksComponent.vue";
  const Blocks = __component__$3.exports;
  const BlockTitle_vue_vue_type_style_index_0_lang = "";
  const props = {
    props: {
      content: {
        default: () => ({}),
        type: [Array, Object]
      },
      fieldset: {
        default: () => ({}),
        type: Object
      }
    }
  };
  const _sfc_main$2 = {
    mixins: [props],
    inheritAttrs: false,
    computed: {
      preview() {
        return this.fieldset.previewObj;
      },
      name() {
        var _a;
        let item = this.preview.name;
        return this.parse(item, (_a = this.fieldset.name) != null ? _a : this.fieldset.label);
      },
      label() {
        let item = this.preview.label;
        let label = this.parse(item, this.fieldset.label);
        if (label === this.name) {
          return false;
        }
        return label;
      },
      icon() {
        var _a;
        let item = this.preview.icon;
        let icon = this.parse(item, this.fieldset.icon).toLowerCase();
        if (this.hasIcon(icon)) {
          return icon;
        }
        return (_a = item.default) != null ? _a : this.fieldset.icon;
      },
      hasImage() {
        return this.preview.image !== null;
      },
      image() {
        var _a, _b;
        let field = this.preview.image.field;
        let image = (_b = (_a = this.content[field][0]) == null ? void 0 : _a.image) != null ? _b : [];
        return Object.assign({}, this.preview.image, image);
      }
    },
    methods: {
      hasIcon(icon) {
        const iconElement = document.querySelector(
          "svg.k-icons > defs > #icon-" + icon
        );
        return iconElement !== null;
      },
      parse(preview, _default) {
        var _a;
        let value = (preview == null ? void 0 : preview.field) ? "{{" + preview.field + "}}" : _default != null ? _default : "";
        value = this.$helper.string.template(value, this.content);
        if (preview == null ? void 0 : preview.default) {
          _default = preview.default;
        }
        if (preview == null ? void 0 : preview.options) {
          value = (_a = preview.options[value]) != null ? _a : _default;
        }
        if (value === "\u2026" || value === void 0 || value === "") {
          return _default != null ? _default : false;
        }
        value = this.$helper.string.stripHTML(value);
        return this.$helper.string.unescapeHTML(value);
      }
    }
  };
  var _sfc_render$2 = function render() {
    var _vm = this, _c = _vm._self._c;
    return _c("div", { staticClass: "k-block-title" }, [_vm.hasImage ? _c("div", { staticClass: "k-block-title-image-wrapper" }, [_c("k-image-frame", _vm._b({}, "k-image-frame", _vm.image, false))], 1) : _c("k-icon", { staticClass: "k-block-icon", attrs: { "type": _vm.icon } }), _vm.name ? _c("span", { staticClass: "k-block-name" }, [_vm._v(" " + _vm._s(_vm.name) + " ")]) : _vm._e(), _vm.label ? _c("span", { staticClass: "k-block-label" }, [_vm._v(" " + _vm._s(_vm.label) + " ")]) : _vm._e()], 1);
  };
  var _sfc_staticRenderFns$2 = [];
  _sfc_render$2._withStripped = true;
  var __component__$2 = /* @__PURE__ */ normalizeComponent(
    _sfc_main$2,
    _sfc_render$2,
    _sfc_staticRenderFns$2,
    false,
    null,
    null,
    null,
    null
  );
  __component__$2.options.__file = "/Users/romangsponer/Cloud/_sites/plugin-env/site/plugins/kirby-blocks-suite/src/Components/BlockTitle.vue";
  const BlockTitle = __component__$2.exports;
  const FormComponent_vue_vue_type_style_index_0_lang = "";
  const _sfc_main$1 = {
    inheritAttrs: false,
    props: {
      endpoints: Object,
      hidden: {
        type: Boolean,
        default() {
          return false;
        }
      },
      icon: String,
      id: String,
      tabs: Object,
      type: String,
      value: Object
    },
    data() {
      return {
        tab: null
      };
    },
    computed: {
      currentTab() {
        var _a, _b, _c, _d;
        if (this.tab === null) {
          this.tab = (_b = (_a = this.currentTabs.filter((a) => a.active)) == null ? void 0 : _a[0]) == null ? void 0 : _b.name;
        }
        let available = this.currentTabs.filter((a) => a.name === this.tab);
        if (available.length > 0) {
          return this.tab;
        }
        return (_d = (_c = this == null ? void 0 : this.currentTabs[0]) == null ? void 0 : _c.name) != null ? _d : Object.keys(this.tabs)[0];
      },
      currentTabs() {
        let tabarray = [];
        Object.entries(this.tabs).forEach(([tabName, tab]) => {
          var _a;
          if (Object.keys(this.tabs[tabName].fields).length > 0) {
            tabarray.push({
              name: tabName,
              icon: tab.icon,
              label: tab.label,
              active: (_a = tab.active) != null ? _a : false
            });
          }
        });
        return tabarray;
      },
      parsedTabs() {
        let tabs = this.tabs;
        Object.entries(tabs).forEach(([tabName, tab]) => {
          Object.entries(tab.fields).forEach(([fieldName]) => {
            tabs[tabName].fields[fieldName].section = this.name;
            tabs[tabName].fields[fieldName].endpoints = {
              field: this.endpoints.field + "/_component_/" + this.type.replace("/", "__") + "/fields/" + fieldName,
              section: this.endpoints.section,
              model: this.endpoints.model
            };
          });
        });
        return tabs;
      }
    }
  };
  var _sfc_render$1 = function render() {
    var _vm = this, _c = _vm._self._c;
    return _c("div", { staticClass: "k-component-form", attrs: { "id": _vm.id, "hidden": _vm.hidden || _vm.currentTabs.length === 0 } }, [_c("k-grid", [_vm.currentTabs.length > 1 ? _c("k-column", [_c("k-component-tabs", { attrs: { "tab": _vm.currentTab, "tabs": _vm.currentTabs }, on: { "update": function($event) {
      _vm.tab = $event;
    } } })], 1) : _vm._e(), _c("k-column", _vm._l(_vm.parsedTabs, function(thistab, componentType) {
      return _c("k-form", { key: componentType, ref: "form", refInFor: true, attrs: { "hidden": componentType !== _vm.currentTab, "autofocus": true, "fields": thistab.fields, "value": _vm.value }, on: { "input": function($event) {
        return _vm.$emit("update", $event);
      }, "invalid": function($event) {
        return _vm.$emit("invalid", $event);
      } } });
    }), 1)], 1)], 1);
  };
  var _sfc_staticRenderFns$1 = [];
  _sfc_render$1._withStripped = true;
  var __component__$1 = /* @__PURE__ */ normalizeComponent(
    _sfc_main$1,
    _sfc_render$1,
    _sfc_staticRenderFns$1,
    false,
    null,
    null,
    null,
    null
  );
  __component__$1.options.__file = "/Users/romangsponer/Cloud/_sites/plugin-env/site/plugins/kirby-blocks-suite/src/Components/FormComponent.vue";
  const BlockForm = __component__$1.exports;
  const _sfc_main = {
    extends: "k-tabs",
    inheritAttrs: false,
    methods: {
      button(tab) {
        var _a, _b;
        return {
          link: tab.link,
          current: tab.name === this.current,
          icon: tab.icon,
          title: tab.label,
          text: (_b = (_a = tab.label) != null ? _a : tab.text) != null ? _b : tab.name,
          click: () => this.$emit("update", tab.name)
        };
      }
    }
  };
  const _sfc_render = null;
  const _sfc_staticRenderFns = null;
  var __component__ = /* @__PURE__ */ normalizeComponent(
    _sfc_main,
    _sfc_render,
    _sfc_staticRenderFns,
    false,
    null,
    null,
    null,
    null
  );
  __component__.options.__file = "/Users/romangsponer/Cloud/_sites/plugin-env/site/plugins/kirby-blocks-suite/src/Components/TabsComponent.vue";
  const BlockTabs = __component__.exports;
  window.panel.plugin("plain/blocks-suite", {
    fields: {
      block: BlockField
    },
    components: {
      "k-block-selector": BlockSelectorComponent,
      "k-block-title": BlockTitle,
      "k-block-type-fields": BlockTypeFields,
      "k-block": Block,
      "k-blocks": Blocks,
      "k-block-form": BlockForm,
      "k-block-tabs": BlockTabs
    }
  });
})();
