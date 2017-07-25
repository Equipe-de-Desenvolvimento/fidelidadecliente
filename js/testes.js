(function (c, j) {
    c.widget("ui.dialog", {
        options: {
            autoOpen: true,
            buttons: {},
            closeOnEscape: true,
            closeText: "close",
            dialogClass: "",
            draggable: true,
            hide: null,
            height: "auto",
            maxHeight: false,
            maxWidth: false,
            minHeight: 150,
            minWidth: 150,
            modal: false,
            position: {
                my: "center",
                at: "center",
                of: window,
                collision: "fit",
                using: function (a) {
                    var b = c(this).css(a).offset().top;
                    b < 0 && c(this).css("top", a.top - b)
                }
            },
            resizable: true,
            show: null,
            stack: true,
            title: "",
            width: 300,
            zIndex: 1E3
        },
        _create: function () {
            this.originalTitle = this.element.attr("title");
            if (typeof this.originalTitle !== "string") this.originalTitle = "";
            this.options.title = this.options.title || this.originalTitle;
            var a = this,
            b = a.options,
            d = b.title || "&#160;",
            f = c.ui.dialog.getTitleId(a.element),
            g = (a.uiDialog = c("<div></div>")).appendTo(document.body).hide().addClass("ui-dialog ui-widget ui-widget-content ui-corner-all " + b.dialogClass).css({
                zIndex: b.zIndex
            }).attr("tabIndex", -1).css("outline", 0).keydown(function (i) {
                if (b.closeOnEscape && i.keyCode && i.keyCode === c.ui.keyCode.ESCAPE) {
                    a.close(i);
                    i.preventDefault()
                }
            }).attr({
                role: "dialog",
                "aria-labelledby": f
            }).mousedown(function (i) {
                a.moveToTop(false, i)
            });
            a.element.show().removeAttr("title").addClass("ui-dialog-content ui-widget-content").appendTo(g);
            var e = (a.uiDialogTitlebar = c("<div></div>")).addClass("ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix").prependTo(g),
            h = c('<a href="#"></a>').addClass("ui-dialog-titlebar-close ui-corner-all").attr("role", "button").hover(function () {
                h.addClass("ui-state-hover")
            }, function () {
                h.removeClass("ui-state-hover")
            }).focus(function () {
                h.addClass("ui-state-focus")
            }).blur(function () {
                h.removeClass("ui-state-focus")
            }).click(function (i) {
                a.close(i);
                return false
            }).appendTo(e);
            (a.uiDialogTitlebarCloseText = c("<span></span>")).addClass("ui-icon ui-icon-closethick").text(b.closeText).appendTo(h);
            c("<span></span>").addClass("ui-dialog-title").attr("id", f).html(d).prependTo(e);
            if (c.isFunction(b.beforeclose) && !c.isFunction(b.beforeClose)) b.beforeClose = b.beforeclose;
            e.find("*").add(e).disableSelection();
            b.draggable && c.fn.draggable && a._makeDraggable();
            b.resizable && c.fn.resizable && a._makeResizable();
            a._createButtons(b.buttons);
            a._isOpen = false;
            c.fn.bgiframe && g.bgiframe()
        },
        _init: function () {
            this.options.autoOpen && this.open()
        },
        destroy: function () {
            var a = this;
            a.overlay && a.overlay.destroy();
            a.uiDialog.hide();
            a.element.unbind(".dialog").removeData("dialog").removeClass("ui-dialog-content ui-widget-content").hide().appendTo("body");
            a.uiDialog.remove();
            a.originalTitle && a.element.attr("title", a.originalTitle);
            return a
        },
        widget: function () {
            return this.uiDialog
        },
        close: function (a) {
            var b = this,
            d;
            if (false !== b._trigger("beforeClose", a)) {
                b.overlay && b.overlay.destroy();
                b.uiDialog.unbind("keypress.ui-dialog");
                b._isOpen = false;
                if (b.options.hide) b.uiDialog.hide(b.options.hide, function () {
                    b._trigger("close", a)
                });
                else {
                    b.uiDialog.hide();
                    b._trigger("close", a)
                }
                c.ui.dialog.overlay.resize();
                if (b.options.modal) {
                    d = 0;
                    c(".ui-dialog").each(function () {
                        if (this !== b.uiDialog[0]) d = Math.max(d, c(this).css("z-index"))
                    });
                    c.ui.dialog.maxZ = d
                }
                return b
            }
        },
        isOpen: function () {
            return this._isOpen
        },
        moveToTop: function (a, b) {
            var d = this,
            f = d.options;
            if (f.modal && !a || !f.stack && !f.modal) return d._trigger("focus", b);
            if (f.zIndex > c.ui.dialog.maxZ) c.ui.dialog.maxZ = f.zIndex;
            if (d.overlay) {
                c.ui.dialog.maxZ += 1;
                d.overlay.$el.css("z-index", c.ui.dialog.overlay.maxZ = c.ui.dialog.maxZ)
            }
            a = {
                scrollTop: d.element.attr("scrollTop"),
                scrollLeft: d.element.attr("scrollLeft")
            };
            c.ui.dialog.maxZ += 1;
            d.uiDialog.css("z-index", c.ui.dialog.maxZ);
            d.element.attr(a);
            d._trigger("focus", b);
            return d
        },
        open: function () {
            if (!this._isOpen) {
                var a = this,
                b = a.options,
                d = a.uiDialog;
                a.overlay = b.modal ? new c.ui.dialog.overlay(a) : null;
                d.next().length && d.appendTo("body");
                a._size();
                a._position(b.position);
                d.show(b.show);
                a.moveToTop(true);
                b.modal && d.bind("keypress.ui-dialog", function (f) {
                    if (f.keyCode === c.ui.keyCode.TAB) {
                        var g = c(":tabbable", this),
                        e = g.filter(":first");
                        g = g.filter(":last");
                        if (f.target === g[0] && !f.shiftKey) {
                            e.focus(1);
                            return false
                        } else if (f.target === e[0] && f.shiftKey) {
                            g.focus(1);
                            return false
                        }
                    }
                });
                c(a.element.find(":tabbable").get().concat(d.find(".ui-dialog-buttonpane :tabbable").get().concat(d.get()))).eq(0).focus();
                a._isOpen = true;
                a._trigger("open");
                return a
            }
        },
        _createButtons: function (a) {
            var b = this,
            d = false,
            f = c("<div></div>").addClass("ui-dialog-buttonpane ui-widget-content ui-helper-clearfix"),
            g = c("<div></div>").addClass("ui-dialog-buttonset").appendTo(f);
            b.uiDialog.find(".ui-dialog-buttonpane").remove();
            typeof a === "object" && a !== null && c.each(a, function () {
                return !(d = true)
            });
            if (d) {
                c.each(a, function (e, h) {
                    h = c.isFunction(h) ? {
                        click: h,
                        text: e
                    } : h;
                    e = c("<button></button>", h).unbind("click").click(function () {
                        h.click.apply(b.element[0], arguments)
                    }).appendTo(g);
                    c.fn.button && e.button()
                });
                f.appendTo(b.uiDialog)
            }
        },
        _makeDraggable: function () {
            function a(e) {
                return {
                    position: e.position,
                    offset: e.offset
                }
            }
            var b = this,
            d = b.options,
            f = c(document),
            g;
            b.uiDialog.draggable({
                cancel: ".ui-dialog-content, .ui-dialog-titlebar-close",
                handle: ".ui-dialog-titlebar",
                containment: "document",
                start: function (e, h) {
                    g = d.height === "auto" ? "auto" : c(this).height();
                    c(this).height(c(this).height()).addClass("ui-dialog-dragging");
                    b._trigger("dragStart", e, a(h))
                },
                drag: function (e, h) {
                    b._trigger("drag", e, a(h))
                },
                stop: function (e, h) {
                    d.position = [h.position.left - f.scrollLeft(), h.position.top - f.scrollTop()];
                    c(this).removeClass("ui-dialog-dragging").height(g);
                    b._trigger("dragStop", e, a(h));
                    c.ui.dialog.overlay.resize()
                }
            })
        },
        _makeResizable: function (a) {
            function b(e) {
                return {
                    originalPosition: e.originalPosition,
                    originalSize: e.originalSize,
                    position: e.position,
                    size: e.size
                }
            }
            a = a === j ? this.options.resizable : a;
            var d = this,
            f = d.options,
            g = d.uiDialog.css("position");
            a = typeof a === "string" ? a : "n,e,s,w,se,sw,ne,nw";
            d.uiDialog.resizable({
                cancel: ".ui-dialog-content",
                containment: "document",
                alsoResize: d.element,
                maxWidth: f.maxWidth,
                maxHeight: f.maxHeight,
                minWidth: f.minWidth,
                minHeight: d._minHeight(),
                handles: a,
                start: function (e, h) {
                    c(this).addClass("ui-dialog-resizing");
                    d._trigger("resizeStart", e, b(h))
                },
                resize: function (e, h) {
                    d._trigger("resize", e, b(h))
                },
                stop: function (e, h) {
                    c(this).removeClass("ui-dialog-resizing");
                    f.height = c(this).height();
                    f.width = c(this).width();
                    d._trigger("resizeStop", e, b(h));
                    c.ui.dialog.overlay.resize()
                }
            }).css("position", g).find(".ui-resizable-se").addClass("ui-icon ui-icon-grip-diagonal-se")
        },
        _minHeight: function () {
            var a = this.options;
            return a.height === "auto" ? a.minHeight : Math.min(a.minHeight, a.height)
        },
        _position: function (a) {
            var b = [],
            d = [0, 0],
            f;
            if (a) {
                if (typeof a === "string" || typeof a === "object" && "0" in a) {
                    b = a.split ? a.split(" ") : [a[0], a[1]];
                    if (b.length === 1) b[1] = b[0];
                    c.each(["left", "top"], function (g, e) {
                        if (+b[g] === b[g]) {
                            d[g] = b[g];
                            b[g] = e
                        }
                    });
                    a = {
                        my: b.join(" "),
                        at: b.join(" "),
                        offset: d.join(" ")
                    }
                }
                a = c.extend({}, c.ui.dialog.prototype.options.position, a)
            } else a = c.ui.dialog.prototype.options.position;
            (f = this.uiDialog.is(":visible")) || this.uiDialog.show();
            this.uiDialog.css({
                top: 0,
                left: 0
            }).position(a);
            f || this.uiDialog.hide()
        },
        _setOption: function (a, b) {
            var d = this,
            f = d.uiDialog,
            g = f.is(":data(resizable)"),
            e = false;
            switch (a) {
                case "beforeclose":
                    a = "beforeClose";
                    break;
                case "buttons":
                    d._createButtons(b);
                    e = true;
                    break;
                case "closeText":
                    d.uiDialogTitlebarCloseText.text("" + b);
                    break;
                case "dialogClass":
                    f.removeClass(d.options.dialogClass).addClass("ui-dialog ui-widget ui-widget-content ui-corner-all " + b);
                    break;
                case "disabled":
                    b ? f.addClass("ui-dialog-disabled") : f.removeClass("ui-dialog-disabled");
                    break;
                case "draggable":
                    b ? d._makeDraggable() : f.draggable("destroy");
                    break;
                case "height":
                    e = true;
                    break;
                case "maxHeight":
                    g && f.resizable("option", "maxHeight", b);
                    e = true;
                    break;
                case "maxWidth":
                    g && f.resizable("option", "maxWidth", b);
                    e = true;
                    break;
                case "minHeight":
                    g && f.resizable("option", "minHeight", b);
                    e = true;
                    break;
                case "minWidth":
                    g && f.resizable("option", "minWidth", b);
                    e = true;
                    break;
                case "position":
                    d._position(b);
                    break;
                case "resizable":
                    g && !b && f.resizable("destroy");
                    g && typeof b === "string" && f.resizable("option", "handles", b);
                    !g && b !== false && d._makeResizable(b);
                    break;
                case "title":
                    c(".ui-dialog-title", d.uiDialogTitlebar).html("" + (b || "&#160;"));
                    break;
                case "width":
                    e = true;
                    break
            }
            c.Widget.prototype._setOption.apply(d, arguments);
            e && d._size()
        },
        _size: function () {
            var a = this.options,
            b;
            this.element.css({
                width: "auto",
                minHeight: 0,
                height: 0
            });
            if (a.minWidth > a.width) a.width = a.minWidth;
            b = this.uiDialog.css({
                height: "auto",
                width: a.width
            }).height();
            this.element.css(a.height === "auto" ? {
                minHeight: Math.max(a.minHeight - b, 0),
                height: c.support.minHeight ? "auto" : Math.max(a.minHeight - b, 0)
            } : {
                minHeight: 0,
                height: Math.max(a.height - b, 0)
            }).show();
            this.uiDialog.is(":data(resizable)") && this.uiDialog.resizable("option", "minHeight", this._minHeight())
        }
    });
    c.extend(c.ui.dialog, {
        version: "1.8.5",
        uuid: 0,
        maxZ: 0,
        getTitleId: function (a) {
            a = a.attr("id");
            if (!a) {
                this.uuid += 1;
                a = this.uuid
            }
            return "ui-dialog-title-" + a
        },
        overlay: function (a) {
            this.$el = c.ui.dialog.overlay.create(a)
        }
    });
    c.extend(c.ui.dialog.overlay, {
        instances: [],
        oldInstances: [],
        maxZ: 0,
        events: c.map("focus,mousedown,mouseup,keydown,keypress,click".split(","), function (a) {
            return a + ".dialog-overlay"
        }).join(" "),
        create: function (a) {
            if (this.instances.length === 0) {
                setTimeout(function () {
                    c.ui.dialog.overlay.instances.length && c(document).bind(c.ui.dialog.overlay.events, function (d) {
                        if (c(d.target).zIndex() < c.ui.dialog.overlay.maxZ) return false
                    })
                }, 1);
                c(document).bind("keydown.dialog-overlay", function (d) {
                    if (a.options.closeOnEscape && d.keyCode && d.keyCode === c.ui.keyCode.ESCAPE) {
                        a.close(d);
                        d.preventDefault()
                    }
                });
                c(window).bind("resize.dialog-overlay", c.ui.dialog.overlay.resize)
            }
            var b = (this.oldInstances.pop() || c("<div></div>").addClass("ui-widget-overlay")).appendTo(document.body).css({
                width: this.width(),
                height: this.height()
            });
            c.fn.bgiframe && b.bgiframe();
            this.instances.push(b);
            return b
        },
        destroy: function (a) {
            this.oldInstances.push(this.instances.splice(c.inArray(a, this.instances), 1)[0]);
            this.instances.length === 0 && c([document, window]).unbind(".dialog-overlay");
            a.remove();
            var b = 0;
            c.each(this.instances, function () {
                b = Math.max(b, this.css("z-index"))
            });
            this.maxZ = b
        },
        height: function () {
            var a, b;
            if (c.browser.msie && c.browser.version < 7) {
                a = Math.max(document.documentElement.scrollHeight, document.body.scrollHeight);
                b = Math.max(document.documentElement.offsetHeight, document.body.offsetHeight);
                return a < b ? c(window).height() + "px" : a + "px"
            } else return c(document).height() + "px"
        },
        width: function () {
            var a, b;
            if (c.browser.msie && c.browser.version < 7) {
                a = Math.max(document.documentElement.scrollWidth, document.body.scrollWidth);
                b = Math.max(document.documentElement.offsetWidth, document.body.offsetWidth);
                return a < b ? c(window).width() + "px" : a + "px"
            } else return c(document).width() + "px"
        },
        resize: function () {
            var a = c([]);
            c.each(c.ui.dialog.overlay.instances, function () {
                a = a.add(this)
            });
            a.css({
                width: 0,
                height: 0
            }).css({
                width: c.ui.dialog.overlay.width(),
                height: c.ui.dialog.overlay.height()
            })
        }
    });
    c.extend(c.ui.dialog.overlay.prototype, {
        destroy: function () {
            c.ui.dialog.overlay.destroy(this.$el)
        }
    })
})(jQuery);
;