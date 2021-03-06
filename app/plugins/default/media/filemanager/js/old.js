function clear_clipboard() {
    bootbox.confirm($("#lang_clear_clipboard_confirm").val(), $("#cancel").val(), $("#ok").val(), function (e) {
        if (e == true) {
            $.ajax({
                type: "POST",
                url: "ajax_calls.php?action=clear_clipboard",
                data: {}
            }).done(function (e) {
                if (e != "") bootbox.alert(e);
                else $("#clipboard").val("0");
                toggle_clipboard(false)
            })
        }
    })
}

function copy_cut_clicked(e, t) {
    if (t != "copy" && t != "cut") {
        return
    }
    if (!e.hasClass("directory")) {
        var n = e.find(".rename-file").attr("data-thumb");
        var r = e.find(".rename-file").attr("data-path")
    } else {
        var n = e.find(".rename-folder").attr("data-thumb");
        var r = e.find(".rename-folder").attr("data-path")
    }
    $.ajax({
        type: "POST",
        url: "ajax_calls.php?action=copy_cut",
        data: {
            path: r,
            path_thumb: n,
            sub_action: t
        }
    }).done(function (e) {
        if (e != "") {
            bootbox.alert(e)
        } else {
            $("#clipboard").val("1");
            toggle_clipboard(true)
        }
    })
}

function paste_to_this_dir(e) {
    bootbox.confirm($("#lang_paste_confirm").val(), $("#cancel").val(), $("#ok").val(), function (t) {
        if (t == true) {
            if (typeof e != "undefined") {
                var n = e.find(".rename-folder").attr("data-path");
                var r = e.find(".rename-folder").attr("data-thumb")
            } else {
                var n = $("#sub_folder").val() + $("#fldr_value").val();
                var r = $("#cur_dir_thumb").val()
            }
            $.ajax({
                type: "POST",
                url: "execute.php?action=paste_clipboard",
                data: {
                    path: n,
                    path_thumb: r
                }
            }).done(function (e) {
                if (e != "") {
                    bootbox.alert(e)
                } else {
                    $("#clipboard").val("0");
                    toggle_clipboard(false);
                    setTimeout(function () {
                        window.location.href = $("#refresh").attr("href") + "&" + (new Date).getTime()
                    }, 300)
                }
            })
        }
    })
}

function drag_n_drop_paste(e, t) {
    if (!e.hasClass("directory")) {
        var n = e.find(".rename-file").attr("data-thumb");
        var r = e.find(".rename-file").attr("data-path")
    } else {
        var n = e.find(".rename-folder").attr("data-thumb");
        var r = e.find(".rename-folder").attr("data-path")
    }
    $.ajax({
        type: "POST",
        url: "ajax_calls.php?action=copy_cut",
        data: {
            path: r,
            path_thumb: n,
            sub_action: "cut"
        }
    }).done(function (e) {
        if (e != "") {
            bootbox.alert(e)
        } else {
            if (typeof t != "undefined") {
                var n = t.find(".rename-folder").attr("data-path");
                var r = t.find(".rename-folder").attr("data-thumb")
            } else {
                var n = $("#sub_folder").val() + $("#fldr_value").val();
                var r = $("#cur_dir_thumb").val()
            }
            $.ajax({
                type: "POST",
                url: "execute.php?action=paste_clipboard",
                data: {
                    path: n,
                    path_thumb: r
                }
            }).done(function (e) {
                if (e != "") {
                    bootbox.alert(e)
                } else {
                    $("#clipboard").val("0");
                    toggle_clipboard(false);
                    setTimeout(function () {
                        window.location.href = $("#refresh").attr("href") + "&" + (new Date).getTime()
                    }, 300)
                }
            })
        }
    })
}

function toggle_clipboard(e) {
    if (e == true) {
        $(".paste-here-btn, .clear-clipboard-btn").removeClass("disabled")
    } else {
        $(".paste-here-btn, .clear-clipboard-btn").addClass("disabled")
    }
}

function fix_colums(e) {
    var t = $(".breadcrumb").width() + e;
    $(".uploader").css("width", t);
    if ($("#view").val() > 0) {
        if ($("#view").val() == 1) {
            $("ul.grid li, ul.grid figure").css("width", "100%")
        } else {
            var n = Math.floor(t / 380);
            if (n == 0) {
                n = 1;
                $("h4").css("font-size", 12)
            }
            t = Math.floor(t / n - 3);
            $("ul.grid li, ul.grid figure").css("width", t)
        }
        $("#help").hide()
    } else {
        if (Modernizr.touch) {
            $("#help").show()
        }
    }
}

function swipe_reaction(e, t, n, r, i) {
    var s = $(this);
    if ($("#view").val() == 0) {
        if (s.attr("toggle") == 1) {
            s.attr("toggle", 0);
            s.animate({
                top: "0px"
            }, {
                queue: false,
                duration: 300
            })
        } else {
            s.attr("toggle", 1);
            s.animate({
                top: "-30px"
            }, {
                queue: false,
                duration: 300
            })
        }
    }
}

function apply(e, t) {
    if ($("#popup").val() == 1) var n = window.opener;
    else var n = window.parent;
    var r = $("#cur_dir").val();
    var i = $("#base_url").val();
    var s = e.substr(0, e.lastIndexOf("."));
    var o = e.split(".").pop();
    o = o.toLowerCase();
    var u = "";
    var a = new Array("ogg", "mp3", "wav");
    var f = new Array("mp4", "ogg", "webm");
    if ($.inArray(o, ext_img) > -1) {
        u = '<img src="' + i + r + e + '" alt="' + s + '" />'
    } else {
        if ($.inArray(o, f) > -1) {
            u = '<video controls source src="' + i + r + e + '" type="video/' + o + '">' + s + "</video>"
        } else {
            if ($.inArray(o, a) > -1) {
                if (o == "mp3") {
                    o = "mpeg"
                }
                u = '<audio controls src="' + i + r + e + '" type="audio/' + o + '">' + s + "</audio>"
            } else {
                u = '<a href="' + i + r + e + '" title="' + s + '">' + s + "</a>"
            }
        }
    }
    parent.tinymce.activeEditor.insertContent(u);
    parent.tinymce.activeEditor.windowManager.close()
}

function apply_link(e, t) {
    if ($("#popup").val() == 1) var n = window.opener;
    else var n = window.parent;
    var r = $("#cur_dir").val();
    r = r.replace("\\", "/");
    var i = $("#base_url").val();
    if (t != "") {
        var s = $("#" + t, n.document);
        $(s).val(i + r + e);
        $(s).trigger("change");
        close_window()
    } else apply_any(i + r, e)
}

function apply_img(e, t) {
    if ($("#popup").val() == 1) var n = window.opener;
    else var n = window.parent;
    var r = $("#cur_dir").val();
    r = r.replace("\\", "/");
    r = r.charAt(0) === "/" ? r.substring(1) : r;
    var i = $("#base_url").val();
    if (t != "") {
        var s = $("#" + t, n.document);
        $(s).val(i + r + e);
        $(s).trigger("change");
        close_window()
    } else apply_any(i + r, e)
}

function apply_video(e, t) {
    if ($("#popup").val() == 1) var n = window.opener;
    else var n = window.parent;
    var r = $("#cur_dir").val();
    r = r.replace("\\", "/");
    var i = $("#base_url").val();
    if (t != "") {
        var s = $("#" + t, n.document);
        $(s).val(i + r + e);
        $(s).trigger("change");
        close_window()
    } else apply_any(r, e)
}

function apply_none(e, t) {
    var n = $('li[data-name="' + e + '"]').find(".preview");
    if (n.html() != "" && n.html() !== undefined) {
        $("#full-img").attr("src", decodeURIComponent(n.attr("data-url")));
        if (n.hasClass("disabled") == false) {
            show_animation();
            $("#previewLightbox").lightbox()
        }
    } else {
        var n = $('li[data-name="' + e + '"]').find(".modalAV");
        $("#previewAV").removeData("modal");
        $("#previewAV").modal({
            backdrop: "static",
            keyboard: false
        });
        if (n.hasClass("audio")) {
            $(".body-preview").css("height", "80px")
        } else {
            $(".body-preview").css("height", "345px")
        }
        $.ajax({
            url: decodeURIComponent(n.attr("data-url")),
            success: function (e) {
                $(".body-preview").html(e)
            }
        })
    }
    return
}

function apply_any(e, t) {
    e = $("#base_url_true").val() + $("#cur_dir_full").val();
    parent.tinymce.activeEditor.windowManager.getParams().setUrl(e + t);
    parent.tinymce.activeEditor.windowManager.close();
    return false
}

function close_window() {
    if ($("#popup").val() == 1) window.close();
    else {
        if (typeof parent.jQuery !== "undefined" && parent.jQuery) {
            parent.jQuery.fancybox.close()
        } else {
            parent.$.fancybox.close()
        }
    }
}

function apply_file_duplicate(e, t) {
    var n = e.parent().parent().parent().parent();
    n.after("<li class='" + n.attr("class") + "' data-name='" + n.attr("data-name") + "'>" + n.html() + "</li>");
    var r = n.next();
    apply_file_rename(r.find("figure"), t);
    var i = r.find(".download-form");
    var s = "form" + (new Date).getTime();
    i.attr("id", s);
    i.find(".tip-right").attr("onclick", "$('#" + s + "').submit();")
}

function apply_file_rename(e, t) {
    e.attr("data-name", t);
    e.parent().attr("data-name", t);
    e.find("h4").find("a").text(t);
    var n = e.find("a.link");
    var r = n.attr("data-file");
    var i = r.substring(r.lastIndexOf("/") + 1);
    var s = r.substring(r.lastIndexOf(".") + 1);
    n.each(function () {
        $(this).attr("data-file", encodeURIComponent(t + "." + s))
    });
    e.find("img").each(function () {
        var e = $(this).attr("src");
        $(this).attr("src", e.replace(i, t + "." + s));
        $(this).attr("alt", t + " thumbnails")
    });
    var o = e.find("a.preview");
    var r = o.attr("data-url");
    if (typeof r !== "undefined" && r) {
        o.attr("data-url", r.replace(encodeURIComponent(i), encodeURIComponent(t + "." + s)))
    }
    e.parent().attr("data-name", t + "." + s);
    e.attr("data-name", t + "." + s);
    e.find(".name_download").val(t + "." + s);
    var u = e.find("a.rename-file");
    var a = e.find("a.delete-file");
    var f = u.attr("data-path");
    var l = u.attr("data-thumb");
    var c = f.replace(i, t + "." + s);
    var h = l.replace(i, t + "." + s);
    u.attr("data-path", c);
    u.attr("data-thumb", h);
    a.attr("data-path", c);
    a.attr("data-thumb", h)
}

function apply_folder_rename(e, t) {
    e.attr("data-name", t);
    e.find("figure").attr("data-name", t);
    var n = e.find("h4").find("a").text();
    e.find("h4 > a").text(t);
    var r = e.find(".folder-link");
    var i = r.attr("href");
    var s = $("#fldr_value").val();
    var o = i.replace("fldr=" + s + encodeURIComponent(n), "fldr=" + s + encodeURIComponent(t));
    r.each(function () {
        $(this).attr("href", o)
    });
    var u = e.find("a.delete-folder");
    var a = e.find("a.rename-folder");
    var f = a.attr("data-path");
    var l = a.attr("data-thumb");
    var c = f.lastIndexOf("/");
    var h = f.substr(0, c + 1) + t;
    u.attr("data-path", h);
    a.attr("data-path", h);
    var c = l.lastIndexOf("/");
    var h = l.substr(0, c + 1) + t;
    u.attr("data-thumb", h);
    a.attr("data-thumb", h)
}

function replace_last(e, t, n) {
    var r = new RegExp(t + "$");
    return e.replace(r, n)
}

function replaceDiacritics(e) {
    var e;
    var t = [/[\300-\306]/g, /[\340-\346]/g, /[\310-\313]/g, /[\350-\353]/g, /[\314-\317]/g, /[\354-\357]/g, /[\322-\330]/g, /[\362-\370]/g, /[\331-\334]/g, /[\371-\374]/g, /[\321]/g, /[\361]/g, /[\307]/g, /[\347]/g];
    var n = ["A", "a", "E", "e", "I", "i", "O", "o", "U", "u", "N", "n", "C", "c"];
    for (var r = 0; r < t.length; r++) {
        e = e.replace(t[r], n[r])
    }
    return e
}

function fix_filename(e) {
    if (e != null) {
        if ($("#transliteration").val() == "true") {
            e = replaceDiacritics(e);
            e = e.replace(/[^A-Za-z0-9\.\-\[\]\ \_]+/g, "")
        }
        e = e.replace('"', "");
        e = e.replace("'", "");
        e = e.replace("/", "");
        e = e.replace("\\", "");
        e = e.replace(/<\/?[^>]+(>|$)/g, "");
        return $.trim(e)
    }
    return null
}

function execute_action(e, t, n, r, i, s) {
    if (r !== null) {
        r = fix_filename(r);
        $.ajax({
            type: "POST",
            url: "execute.php?action=" + e,
            data: {
                path: t,
                path_thumb: n,
                name: r.replace("/", "")
            }
        }).done(function (e) {
            if (e != "") {
                bootbox.alert(e);
                return false
            } else {
                if (s != "") {
                    window[s](i, r)
                }
            }
            return true
        })
    }
}

function sortUnorderedList(e, t, n) {
    if (typeof e == "string") e = $(e);
    var r = e.find("li.dir");
    var i = e.find("li.file");
    var s = [];
    var o = [];
    var u = [];
    var a = [];
    $.each(r, function (e) {
        var t = $(this);
        var r = t.find(n).val();
        if ($.isNumeric(r)) {
            r = parseFloat(r);
            while (typeof s[r] !== "undefined" && s[r]) {
                r = parseFloat(parseFloat(r) + parseFloat(.001))
            }
        } else {
            r = r + "a" + t.find("h4 a").attr("data-file")
        }
        s[r] = t.html();
        o.push(r)
    });
    $.each(i, function (e) {
        var t = $(this);
        var r = t.find(n).val();
        if ($.isNumeric(r)) {
            r = parseFloat(r);
            while (typeof u[r] !== "undefined" && u[r]) {
                r = parseFloat(parseFloat(r) + parseFloat(.001))
            }
        } else {
            r = r + "a" + t.find("h4 a").attr("data-file")
        }
        u[r] = t.html();
        a.push(r)
    });
    if ($.isNumeric(o[0])) {
        o.sort(function (e, t) {
            return parseFloat(e) - parseFloat(t)
        })
    } else {
        o.sort()
    } if ($.isNumeric(a[0])) {
        a.sort(function (e, t) {
            return parseFloat(e) - parseFloat(t)
        })
    } else {
        a.sort()
    } if (t) {
        o.reverse();
        a.reverse()
    }
    $.each(r, function (e) {
        var t = $(this);
        t.html(s[o[e]])
    });
    $.each(i, function (e) {
        var t = $(this);
        t.html(u[a[e]])
    })
}

function show_animation() {
    $("#loading_container").css("display", "block");
    $("#loading").css("opacity", ".7")
}

function hide_animation() {
    $("#loading_container").fadeOut()
}

function launchEditor(e, t) {
    featherEditor.launch({
        image: e,
        url: t
    });
    return false
}
var version = "9.4.0";
var active_contextmenu = true;
if (loading_bar) {
    if (!/MSIE (\d+\.\d+);/.test(navigator.userAgent)) {
        window.addEventListener("DOMContentLoaded", function () {
            $("body").queryLoader2({
                backgroundColor: "none",
                minimumTime: 100,
                percentage: true
            })
        })
    } else {
        $(document).ready(function () {
            $("body").queryLoader2({
                backgroundColor: "none",
                minimumTime: 100,
                percentage: true
            })
        })
    }
}
$(document).ready(function () {
    if (active_contextmenu) {
        $.contextMenu({
            selector: "figure:not(.back-directory), .list-view2 figure:not(.back-directory)",
            autoHide: true,
            build: function (e) {
                e.addClass("selected");
                var t = {
                    callback: function (t, n) {
                        switch (t) {
                        case "copy_url":
                            var r = "";
                            r += $("#base_url").val() + $("#cur_dir").val();
                            add = e.find("a.link").attr("data-file");
                            if (add != "" && add != null) {
                                r += add
                            }
                            add = e.find("h4 a.folder-link").attr("data-file");
                            if (add != "" && add != null) {
                                r += add
                            }
                            bootbox.alert('URL:<br/><br/><input type="text" style="height:30px; width:100%;" value="' + r + '" />');
                            break;
                        case "unzip":
                            var r = $("#sub_folder").val() + $("#fldr_value").val() + e.find("a.link").attr("data-file");
                            $.ajax({
                                type: "POST",
                                url: "ajax_calls.php?action=extract",
                                data: {
                                    path: r
                                }
                            }).done(function (e) {
                                if (e != "") bootbox.alert(e);
                                else window.location.href = $("#refresh").attr("href") + "&" + (new Date).getTime()
                            });
                            break;
                        case "edit_img":
                            var i = e.attr("data-name");
                            var s = $("#base_url_true").val() + $("#cur_dir").val() + i;
                            $("#aviary_img").attr("data-name", i);
                            $("#aviary_img").attr("src", s).load(launchEditor("aviary_img", s));
                            break;
                        case "duplicate":
                            var o = e.find("h4").text().trim();
                            bootbox.prompt($("#lang_duplicate").val(), $("#cancel").val(), $("#ok").val(), function (t) {
                                if (t !== null) {
                                    t = fix_filename(t);
                                    if (t != o) {
                                        var n = e.find(".rename-file");
                                        execute_action("duplicate_file", n.attr("data-path"), n.attr("data-thumb"), t, n, "apply_file_duplicate")
                                    }
                                }
                            }, o);
                            break;
                        case "copy":
                            copy_cut_clicked(e, "copy");
                            break;
                        case "cut":
                            copy_cut_clicked(e, "cut");
                            break;
                        case "paste":
                            paste_to_this_dir();
                            break
                        }
                    },
                    items: {}
                };
                if ((e.find(".img-precontainer-mini .filetype").hasClass("png") || e.find(".img-precontainer-mini .filetype").hasClass("jpg") || e.find(".img-precontainer-mini .filetype").hasClass("jpeg")) && image_editor) {
                    t.items.edit_img = {
                        name: $("#lang_edit_image").val(),
                        icon: "edit_img",
                        disabled: false
                    };
                    t.items.copy_url = {
                        name: $("#lang_show_url").val(),
                        icon: "url",
                        disabled: false
                    }
                }
                if (e.find(".img-precontainer-mini .filetype").hasClass("zip") || e.find(".img-precontainer-mini .filetype").hasClass("tar") || e.find(".img-precontainer-mini .filetype").hasClass("gz")) {
                    t.items.unzip = {
                        name: $("#lang_extract").val(),
                        icon: "extract",
                        disabled: false
                    }
                }
                if (!e.hasClass("directory") && $("#duplicate").val() == 1) {
                    t.items.duplicate = {
                        name: $("#lang_duplicate").val(),
                        icon: "duplicate",
                        disabled: false
                    }
                }
                if (!e.hasClass("directory") && $("#copy_cut_files_allowed").val() == 1) {
                    t.items.copy = {
                        name: $("#lang_copy").val(),
                        icon: "copy",
                        disabled: false
                    };
                    t.items.cut = {
                        name: $("#lang_cut").val(),
                        icon: "cut",
                        disabled: false
                    }
                } else if (e.hasClass("directory") && $("#copy_cut_dirs_allowed").val() == 1) {
                    t.items.copy = {
                        name: $("#lang_copy").val(),
                        icon: "copy",
                        disabled: false
                    };
                    t.items.cut = {
                        name: $("#lang_cut").val(),
                        icon: "cut",
                        disabled: false
                    }
                }
                if ($("#clipboard").val() != 0 && !e.hasClass("directory")) {
                    t.items.paste = {
                        name: $("#lang_paste_here").val(),
                        icon: "clipboard-apply",
                        disabled: false
                    }
                }
                t.items.sep = "----";
                t.items.info = {
                    name: "<strong>" + $("#lang_file_info").val() + "</strong>",
                    disabled: true
                };
                t.items.name = {
                    name: e.attr("data-name"),
                    icon: "label",
                    disabled: true
                };
                if (e.attr("data-type") == "img") {
                    t.items.dimension = {
                        name: e.find(".img-dimension").html(),
                        icon: "dimension",
                        disabled: true
                    }
                }
                t.items.size = {
                    name: e.find(".file-size").html(),
                    icon: "size",
                    disabled: true
                };
                t.items.date = {
                    name: e.find(".file-date").html(),
                    icon: "date",
                    disabled: true
                };
                return t
            },
            events: {
                hide: function (e) {
                    $("figure").removeClass("selected")
                }
            }
        });
        $(document).on("contextmenu", function (e) {
            if (!$(e.target).is("figure")) return false
        })
    }
    $("#full-img").on("click", function () {
        $("#previewLightbox").lightbox("hide")
    });
    $("ul.grid").on("click", ".modalAV", function (e) {
        _this = $(this);
        e.preventDefault();
        $("#previewAV").removeData("modal");
        $("#previewAV").modal({
            backdrop: "static",
            keyboard: false
        });
        if (_this.hasClass("audio")) {
            $(".body-preview").css("height", "80px")
        } else {
            $(".body-preview").css("height", "345px")
        }
        $.ajax({
            url: _this.attr("data-url"),
            success: function (e) {
                $(".body-preview").html(e)
            }
        })
    });
    $("input[name=radio-sort]").on("click", function () {
        var e = $(this).attr("data-item");
        $(".filters label").removeClass("btn-inverse");
        $(".filters label").find("i").removeClass("icon-white");
        $("#filter-input").val("");
        $("#" + e).addClass("btn-inverse");
        $("#" + e).find("i").addClass("icon-white");
        if (e == "ff-item-type-all") {
            $(".grid li").show(300)
        } else {
            if ($(this).is(":checked")) {
                $(".grid li").not("." + e).hide(300);
                $(".grid li." + e).show(300)
            }
        }
    });
    var e = function () {
        var e = 0;
        return function (t, n) {
            clearTimeout(e);
            e = setTimeout(t, n)
        }
    }();
    if (parseInt($("#file_number").val()) > parseInt($("#file_number_limit_js").val())) var t = false;
    else var t = true;
    $("#filter-input").on("keyup", function () {
        $(".filters label").removeClass("btn-inverse");
        $(".filters label").find("i").removeClass("icon-white");
        $("#ff-item-type-all").addClass("btn-inverse");
        $("#ff-item-type-all").find("i").addClass("icon-white");
        var n = fix_filename($(this).val());
        $(this).val(n);
        e(function () {
            if (t) {
                $("ul.grid li").each(function () {
                    var e = $(this);
                    if (n != "" && e.attr("data-name").toString().toLowerCase().indexOf(n.toLowerCase()) == -1) {
                        e.hide(100)
                    } else {
                        e.show(100)
                    }
                })
            }
        }, 300)
    }).keypress(function (e) {
        if (e.which == 13) {
            $("#filter").trigger("click")
        }
    });
    $("#filter").on("click", function () {
        var e = fix_filename($("#filter-input").val());
        window.location.href = $("#current_url").val() + "&filter=" + e
    });
    $("#info").on("click", function () {
        bootbox.alert('<center><img src="img/logo.png" alt="responsive filemanager"/><br/><br/><p><strong>RESPONSIVE filemanager v.' + version + '</strong><br/><a href="http://www.responsivefilemanager.com">responsivefilemanager.com</a></p><br/><p>Copyright Â© <a href="http://www.tecrail.com" alt="tecrail">Tecrail</a> - Alberto Peripolli. All rights reserved.</p><br/><p>License<br/><small>Commercial License</small></p></center>')
    });
    $("#uploader-btn").on("click", function () {
        var e = $("#sub_folder").val() + $("#fldr_value").val() + "/";
        e = e.substring(0, e.length - 1);
        $("#iframe-container").html($("<iframe />", {
            name: "JUpload",
            id: "uploader_frame",
            src: "uploader/index.php?path=" + e,
            frameborder: 0,
            width: "100%",
            height: 360
        }))
    });
    $(".upload-btn").on("click", function () {
        $(".uploader").show(500)
    });
    var n = $("#descending").val() === "true";
    $(".sorter").on("click", function () {
        _this = $(this);
        n = !n;
        if (t) {
            $.ajax({
                url: "ajax_calls.php?action=sort&sort_by=" + _this.attr("data-sort") + "&descending=" + n
            }).done(function (e) {});
            sortUnorderedList("ul.grid", n, "." + _this.attr("data-sort"));
            $(" a.sorter").removeClass("descending").removeClass("ascending");
            if (n) $(".sort-" + _this.attr("data-sort")).addClass("descending");
            else $(".sort-" + _this.attr("data-sort")).addClass("ascending")
        } else {
            window.location.href = $("#current_url").val() + "&sort_by=" + _this.attr("data-sort") + "&descending=" + n
        }
    });
    $(".close-uploader").on("click", function () {
        $(".uploader").hide(500);
        setTimeout(function () {
            window.location.href = $("#refresh").attr("href") + "&" + (new Date).getTime()
        }, 420)
    });
    $("ul.grid").on("click", ".preview", function () {
        var e = $(this);
        $("#full-img").attr("src", decodeURIComponent(e.attr("data-url")));
        if (e.hasClass("disabled") == false) {
            show_animation()
        }
        return true
    });
    $("body").on("keypress", function (e) {
        var t = String.fromCharCode(e.which);
        if (t == "'" || t == '"' || t == "\\" || t == "/") {
            return false
        }
    });
    $("ul.grid").on("click", ".rename-file", function () {
        var e = $(this);
        var t = e.parent().parent().parent();
        var n = t.find("h4");
        var r = $.trim(n.text());
        bootbox.prompt($("#rename").val(), $("#cancel").val(), $("#ok").val(), function (n) {
            if (n !== null) {
                n = fix_filename(n);
                if (n != r) {
                    execute_action("rename_file", e.attr("data-path"), e.attr("data-thumb"), n, t, "apply_file_rename")
                }
            }
        }, r)
    });
    $("ul.grid").on("click", ".rename-folder", function () {
        var e = $(this);
        var t = e.parent().parent().parent();
        var n = t.find("h4");
        var r = $.trim(n.text());
        bootbox.prompt($("#rename").val(), $("#cancel").val(), $("#ok").val(), function (n) {
            if (n !== null) {
                n = fix_filename(n).replace(".", "");
                if (n != r) {
                    execute_action("rename_folder", e.attr("data-path"), e.attr("data-thumb"), n, t, "apply_folder_rename")
                }
            }
        }, r)
    });
    $("ul.grid").on("click", ".delete-file", function () {
        var e = $(this);
        bootbox.confirm(e.attr("data-confirm"), $("#cancel").val(), $("#ok").val(), function (t) {
            if (t == true) {
                execute_action("delete_file", e.attr("data-path"), e.attr("data-thumb"), "", "", "");
                e.parent().parent().parent().parent().remove()
            }
        })
    });
    $("ul.grid").on("click", ".delete-folder", function () {
        var e = $(this);
        bootbox.confirm(e.attr("data-confirm"), $("#cancel").val(), $("#ok").val(), function (t) {
            if (t == true) {
                execute_action("delete_folder", e.attr("data-path"), e.attr("data-thumb"), "", "", "");
                e.parent().parent().parent().remove()
            }
        })
    });
    $(".new-folder").on("click", function () {
        bootbox.prompt($("#insert_folder_name").val(), $("#cancel").val(), $("#ok").val(), function (e) {
            if (e !== null) {
                e = fix_filename(e).replace(".", "");
                var t = $("#sub_folder").val() + $("#fldr_value").val() + e;
                var n = $("#cur_dir_thumb").val() + e;
                $.ajax({
                    type: "POST",
                    url: "execute.php?action=create_folder",
                    data: {
                        path: t,
                        path_thumb: n
                    }
                }).done(function (e) {
                    setTimeout(function () {
                        window.location.href = $("#refresh").attr("href") + "&" + (new Date).getTime()
                    }, 300)
                })
            }
        }, $("#new_folder").val())
    });
    $(".view-controller button").on("click", function () {
        var e = $(this);
        $(".view-controller button").removeClass("btn-inverse");
        $(".view-controller i").removeClass("icon-white");
        e.addClass("btn-inverse");
        e.find("i").addClass("icon-white");
        $.ajax({
            url: "ajax_calls.php?action=view&type=" + e.attr("data-value")
        }).done(function (e) {
            if (e != "") {
                bootbox.alert(e)
            }
        });
        if (typeof $("ul.grid")[0] !== "undefined" && $("ul.grid")[0]) $("ul.grid")[0].className = $("ul.grid")[0].className.replace(/\blist-view.*?\b/g, "");
        if (typeof $(".sorter-container")[0] !== "undefined" && $(".sorter-container")[0]) $(".sorter-container")[0].className = $(".sorter-container")[0].className.replace(/\blist-view.*?\b/g, "");
        var t = e.attr("data-value");
        $("#view").val(t);
        $("ul.grid").addClass("list-view" + t);
        $(".sorter-container").addClass("list-view" + t);
        if (e.attr("data-value") >= 1) {
            fix_colums(14)
        } else {
            $("ul.grid li").css("width", 126);
            $("ul.grid figure").css("width", 122)
        }
    });
    if (!Modernizr.touch) {
        $(".tip").tooltip({
            placement: "bottom"
        });
        $(".tip-top").tooltip({
            placement: "top"
        });
        $(".tip-left").tooltip({
            placement: "left"
        });
        $(".tip-right").tooltip({
            placement: "right"
        });
        $("body").addClass("no-touch")
    } else {
        $("#help").show();
        $(".box:not(.no-effect)").swipe({
            swipeLeft: swipe_reaction,
            swipeRight: swipe_reaction,
            threshold: 30
        })
    }
    $(".paste-here-btn").on("click", function () {
        if ($(this).hasClass("disabled") == false) {
            paste_to_this_dir()
        }
    });
    $(".clear-clipboard-btn").on("click", function () {
        if ($(this).hasClass("disabled") == false) {
            clear_clipboard()
        }
    });
    if (!Modernizr.csstransforms) {
        $("figure").on("mouseover", function () {
            if ($("#view").val() == 0 && $("#main-item-container").hasClass("no-effect-slide") === false) {
                $(this).find(".box:not(.no-effect)").animate({
                    top: "-26px"
                }, {
                    queue: false,
                    duration: 300
                })
            }
        });
        $("figure").on("mouseout", function () {
            if ($("#view").val() == 0) {
                $(this).find(".box:not(.no-effect)").animate({
                    top: "0px"
                }, {
                    queue: false,
                    duration: 300
                })
            }
        })
    }
    $(window).resize(function () {
        fix_colums(28)
    });
    fix_colums(14);
    $("ul.grid").on("click", ".link", function () {
        var e = $(this);
        window[e.attr("data-function")](e.attr("data-file"), e.attr("data-field_id"))
    });
    if ($("#clipboard").val() == 1) {
        toggle_clipboard(true)
    } else {
        toggle_clipboard(false)
    }
    $("li.dir, li.file").draggable({
        revert: true,
        distance: 20,
        cursor: "move",
        helper: function () {
            $(this).find("figure").find(".box").css("top", "0px");
            var e = $(this).clone().css("z-index", 1e3).find(".box").css("box-shadow", "none").css("-webkit-box-shadow", "none").parent().parent();
            $(this).addClass("selected");
            return e
        },
        start: function () {
            if ($("#view").val() == 0) {
                $("#main-item-container").addClass("no-effect-slide")
            }
        },
        stop: function () {
            $(this).removeClass("selected");
            if ($("#view").val() == 0) {
                $("#main-item-container").removeClass("no-effect-slide")
            }
        }
    });
    $("li.dir").droppable({
        accept: "ul.grid li",
        activeClass: "ui-state-highlight",
        hoverClass: "ui-state-highlight",
        drop: function (e, t) {
            drag_n_drop_paste(t.draggable.find("figure"), $(this).find("figure"))
        }
    })
})