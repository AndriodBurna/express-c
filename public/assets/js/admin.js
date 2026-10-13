/* =========================================================================
   EPHRAITECH ADMIN PANEL - interactions (vanilla JS, no dependencies)
   1. Styled delete confirmation anchored next to the clicked button
   2. Modal popup system (edit forms + quote/message viewer)
   3. Quote & message viewer popups (open/edit without leaving the list)
   4. WYSIWYG rich text editor for <textarea class="js-editor">
   5. Auto slug helper
   ========================================================================= */
(function () {
  "use strict";

  function meta(name) {
    var tag = document.querySelector('meta[name="' + name + '"]');
    return tag ? tag.content : "";
  }
  var adminBase = meta("admin-base");
  var csrfToken = meta("csrf-token");
  var adminPage = document.body.dataset.page || "";

  function el(tag, cls, text) {
    var node = document.createElement(tag);
    if (cls) node.className = cls;
    if (text !== undefined && text !== null) node.textContent = text;
    return node;
  }

  function closest(e, selector) {
    return e.target instanceof Element ? e.target.closest(selector) : null;
  }

  /* =======================================================================
     1. Styled delete confirmation ("popconfirm" anchored to the button)
     ======================================================================= */
  var activePop = null;

  function closePopconfirm() {
    if (!activePop) return;
    var pop = activePop.pop;
    activePop = null;
    pop.classList.remove("is-open");
    document.removeEventListener("mousedown", onDocDown, true);
    document.removeEventListener("keydown", onDocKey, true);
    window.removeEventListener("resize", onPopReflow);
    document.removeEventListener("scroll", onPopReflow, true);
    setTimeout(function () {
      if (pop.parentNode) pop.parentNode.removeChild(pop);
    }, 160);
  }

  function onDocDown(e) {
    if (!activePop) return;
    if (activePop.pop.contains(e.target)) return;
    if (activePop.trigger && activePop.trigger.contains(e.target)) return;
    closePopconfirm();
  }

  function onDocKey(e) {
    if (e.key === "Escape") closePopconfirm();
  }

  function onPopReflow() {
    if (activePop) positionPop(activePop.pop, activePop.anchor);
  }

  function positionPop(pop, anchor) {
    var rect = anchor.getBoundingClientRect();
    var margin = 10, gap = 8;
    var left = rect.left + rect.width / 2 - pop.offsetWidth / 2;
    left = Math.max(margin, Math.min(left, window.innerWidth - pop.offsetWidth - margin));
    var top, above = false;
    if (rect.bottom + gap + pop.offsetHeight < window.innerHeight - margin) {
      top = rect.bottom + gap; /* preferred: right under the button */
    } else if (rect.top - gap - pop.offsetHeight > margin) {
      top = rect.top - gap - pop.offsetHeight; /* flip above when near the bottom */
      above = true;
    } else {
      top = Math.max(margin, (window.innerHeight - pop.offsetHeight) / 2);
    }
    pop.classList.toggle("pop-above", above);
    pop.style.left = Math.round(left) + "px";
    pop.style.top = Math.round(top) + "px";
  }

  function showPopconfirm(form, trigger) {
    closePopconfirm();

    var pop = el("div", "popconfirm");
    pop.setAttribute("role", "alertdialog");

    var head = el("div", "popconfirm-head");
    var icon = el("span", "popconfirm-icon");
    icon.innerHTML = '<i class="fa-solid fa-trash-can" aria-hidden="true"></i>';
    head.appendChild(icon);
    head.appendChild(el("div", "popconfirm-title", form.dataset.confirmTitle || "Delete this item?"));

    pop.appendChild(head);
    pop.appendChild(el("p", "popconfirm-text", form.dataset.confirm));

    var actions = el("div", "popconfirm-actions");
    var cancel = el("button", "btn btn-outline btn-sm", "Cancel");
    cancel.type = "button";
    var ok = el("button", "btn btn-danger btn-sm", form.dataset.confirmButton || "Yes, delete");
    ok.type = "button";
    actions.appendChild(cancel);
    actions.appendChild(ok);
    pop.appendChild(actions);

    document.body.appendChild(pop);

    var anchor = trigger || form;
    activePop = { pop: pop, anchor: anchor, trigger: trigger };
    positionPop(pop, anchor);
    void pop.offsetWidth; /* reflow so the transition plays */
    pop.classList.add("is-open");
    ok.focus();

    cancel.addEventListener("click", closePopconfirm);
    ok.addEventListener("click", function () {
      form.dataset.confirmed = "1";
      closePopconfirm();
      if (form.requestSubmit) {
        form.requestSubmit();
      } else {
        form.submit();
      }
    });

    document.addEventListener("mousedown", onDocDown, true);
    document.addEventListener("keydown", onDocKey, true);
    window.addEventListener("resize", onPopReflow);
    document.addEventListener("scroll", onPopReflow, true);
  }

  document.addEventListener("submit", function (e) {
    var form = e.target;
    if (!(form instanceof HTMLFormElement) || !form.dataset.confirm) return;
    if (form.dataset.confirmed === "1") {
      delete form.dataset.confirmed;
      return; /* already confirmed through the popconfirm */
    }
    e.preventDefault();
    e.stopPropagation();
    var trigger = e.submitter || form.querySelector(".btn-danger") || form;
    showPopconfirm(form, trigger);
  });

  /* =======================================================================
     2. Modal popup system
     ======================================================================= */
  function openModal(overlay) {
    if (!overlay) return;
    overlay.classList.add("is-open");
    document.body.classList.add("modal-open");
    var closeBtn = overlay.querySelector("[data-modal-close]");
    if (closeBtn) closeBtn.focus();
  }

  function closeModal(overlay) {
    if (!overlay) return;
    overlay.classList.remove("is-open");
    if (!document.querySelector(".modal-overlay.is-open")) {
      document.body.classList.remove("modal-open");
    }
    if (overlay.dataset.clearParams) {
      var params = new URLSearchParams(window.location.search);
      overlay.dataset.clearParams.split(",").forEach(function (p) { params.delete(p.trim()); });
      var qs = params.toString();
      window.history.replaceState(null, "", window.location.pathname + (qs ? "?" + qs : "") + window.location.hash);
    }
  }

  document.addEventListener("click", function (e) {
    var closer = closest(e, "[data-modal-close]");
    if (closer) {
      e.preventDefault();
      closeModal(closer.closest(".modal-overlay"));
      return;
    }
    if (e.target instanceof Element && e.target.classList.contains("modal-overlay")) {
      closeModal(e.target);
    }
  });

  document.addEventListener("keydown", function (e) {
    if (e.key !== "Escape" || activePop) return;
    var open = document.querySelector(".modal-overlay.is-open");
    if (open) closeModal(open);
  });

  /* Server-rendered popups (e.g. ?edit=… opened the form modal) lock scroll too. */
  if (document.querySelector(".modal-overlay.is-open")) {
    document.body.classList.add("modal-open");
  }

  /* =======================================================================
     3. Quote & message viewer popups
     ======================================================================= */
  function metaCell(label, value) {
    var cell = el("div");
    cell.appendChild(el("small", null, label));
    if (value instanceof Node) cell.appendChild(value);
    else cell.appendChild(document.createTextNode(value || "-"));
    return cell;
  }

  function emailLink(addr) {
    var a = el("a", null, addr);
    a.href = "mailto:" + addr;
    return a;
  }

  function waLink(num) {
    var a = el("a", null, num);
    a.href = "https://wa.me/" + String(num).replace(/[^0-9]/g, "");
    a.target = "_blank";
    a.rel = "noopener";
    return a;
  }

  function hiddenInput(name, value) {
    var input = document.createElement("input");
    input.type = "hidden";
    input.name = name;
    input.value = value;
    return input;
  }

  function postForm(action, fields) {
    var form = document.createElement("form");
    form.method = "post";
    form.action = adminBase + action;
    form.appendChild(hiddenInput("csrf_token", csrfToken));
    Object.keys(fields).forEach(function (k) { form.appendChild(hiddenInput(k, fields[k])); });
    return form;
  }

  function viewerModal(opts) {
    var overlay = el("div", "modal-overlay");
    overlay.id = opts.id;

    var modalEl = el("div", "modal modal-lg");
    modalEl.setAttribute("role", "dialog");
    modalEl.setAttribute("aria-modal", "true");

    var header = el("div", "modal-header");
    var h2 = el("h2");
    var icon = el("i", opts.icon);
    icon.setAttribute("aria-hidden", "true");
    h2.appendChild(icon);
    h2.appendChild(document.createTextNode(opts.title + " "));
    h2.appendChild(el("span", "modal-sub", opts.sub));
    var close = el("button", "modal-close");
    close.type = "button";
    close.setAttribute("data-modal-close", "");
    close.setAttribute("aria-label", "Close");
    close.innerHTML = '<i class="fa-solid fa-xmark" aria-hidden="true"></i>';
    header.appendChild(h2);
    header.appendChild(close);

    var body = el("div", "modal-body");
    var grid = el("div", "meta-grid");
    opts.meta.forEach(function (m) { grid.appendChild(metaCell(m[0], m[1])); });
    body.appendChild(grid);
    body.appendChild(el("div", "msg-body", opts.body));

    var footer = el("div", "modal-footer");
    var left = el("div", "modal-footer-group");
    opts.left.forEach(function (f) { left.appendChild(f); });
    var right = el("div", "modal-footer-group");
    opts.right.forEach(function (f) { right.appendChild(f); });
    footer.appendChild(left);
    footer.appendChild(right);

    modalEl.appendChild(header);
    modalEl.appendChild(body);
    modalEl.appendChild(footer);
    overlay.appendChild(modalEl);
    document.body.appendChild(overlay);
    requestAnimationFrame(function () { openModal(overlay); });
    return overlay;
  }

  function fetchJson(url) {
    return fetch(url, { headers: { "X-Requested-With": "XMLHttpRequest" } }).then(function (r) {
      if (!r.ok) throw new Error("HTTP " + r.status);
      return r.json();
    });
  }

  function openQuote(id) {
    fetchJson(adminBase + "quotes.php?ajax=view&id=" + encodeURIComponent(id))
      .then(function (q) {
        var statusForm = postForm("quotes.php", { id: q.id, action: "status" });
        statusForm.className = "modal-footer-group";
        var lbl = el("label", null, "Status");
        lbl.setAttribute("for", "popup-quote-status");
        var sel = document.createElement("select");
        sel.className = "form-control";
        sel.id = "popup-quote-status";
        sel.name = "status";
        sel.style.width = "auto";
        ["new", "contacted", "in_progress", "closed"].forEach(function (s) {
          var o = document.createElement("option");
          o.value = s;
          o.textContent = s.replace(/_/g, " ").replace(/\b\w/g, function (c) { return c.toUpperCase(); });
          if (q.status === s) o.selected = true;
          sel.appendChild(o);
        });
        var update = el("button", "btn btn-primary btn-sm", "Update status");
        update.type = "submit";
        statusForm.appendChild(lbl);
        statusForm.appendChild(sel);
        statusForm.appendChild(update);

        var delForm = postForm("quotes.php", { id: q.id, action: "delete" });
        delForm.dataset.confirm = "Delete this quote request permanently?";
        var delBtn = el("button", "btn btn-danger btn-sm", "Delete");
        delBtn.type = "submit";
        delForm.appendChild(delBtn);

        viewerModal({
          id: "quoteViewerPopup",
          icon: "fa-solid fa-file-invoice",
          title: "Quote request #" + q.id,
          sub: q.first_name + " " + q.last_name,
          meta: [
            ["Email", emailLink(q.email)],
            ["Phone", q.phone || "-"],
            ["WhatsApp", q.whatsapp ? waLink(q.whatsapp) : "-"],
            ["Company", q.company || "-"],
            ["Service", q.service_needed || "-"],
            ["Budget", q.budget || "-"],
            ["Received", q.created_at],
            ["IP", q.ip_address || "-"]
          ],
          body: q.description,
          left: [statusForm],
          right: [delForm]
        });
      })
      .catch(function () {
        // Fallback: full page view. If we are ALREADY on the deep link, stop —
        // redirecting to the same URL would reload the page forever.
        if (new URLSearchParams(window.location.search).get("view")) return;
        window.location.href = adminBase + "quotes.php?view=" + encodeURIComponent(id);
      });
  }

  function openMessage(id) {
    fetchJson(adminBase + "messages.php?ajax=view&id=" + encodeURIComponent(id))
      .then(function (m) {
        var toggleForm = postForm("messages.php", { id: m.id, action: "toggle_read" });
        var toggleBtn = el("button", "btn btn-outline btn-sm", m.is_read ? "Mark as unread" : "Mark as read");
        toggleBtn.type = "submit";
        toggleForm.appendChild(toggleBtn);

        var delForm = postForm("messages.php", { id: m.id, action: "delete" });
        delForm.dataset.confirm = "Delete this message permanently?";
        var delBtn = el("button", "btn btn-danger btn-sm", "Delete");
        delBtn.type = "submit";
        delForm.appendChild(delBtn);

        viewerModal({
          id: "messageViewerPopup",
          icon: "fa-solid fa-inbox",
          title: "Message #" + m.id,
          sub: m.first_name + " " + m.last_name,
          meta: [
            ["Email", emailLink(m.email)],
            ["Phone", m.phone || "-"],
            ["Subject", m.subject || "-"],
            ["Received", m.created_at]
          ],
          body: m.message,
          left: [toggleForm],
          right: [delForm]
        });

        /* Opening the popup marks the message as read: reflect it on the list. */
        var row = document.querySelector('tr[data-message-id="' + m.id + '"]');
        if (row) {
          var badge = row.querySelector(".badge");
          if (badge) {
            badge.textContent = "Read";
            badge.classList.remove("new");
            badge.classList.add("closed");
          }
        }
      })
      .catch(function () {
        // Fallback: full page view. If we are ALREADY on the deep link, stop —
        // redirecting to the same URL would reload the page forever.
        if (new URLSearchParams(window.location.search).get("view")) return;
        window.location.href = adminBase + "messages.php?view=" + encodeURIComponent(id);
      });
  }

  document.addEventListener("click", function (e) {
    var quoteBtn = closest(e, "[data-open-quote]");
    if (quoteBtn) {
      e.preventDefault();
      openQuote(quoteBtn.dataset.openQuote);
      return;
    }
    var msgBtn = closest(e, "[data-open-message]");
    if (msgBtn) {
      e.preventDefault();
      openMessage(msgBtn.dataset.openMessage);
    }
  });

  /* Deep links (e.g. ?view=12 from the dashboard) open the popup directly. */
  var viewParam = new URLSearchParams(window.location.search).get("view");
  if (viewParam && adminPage === "quotes.php") openQuote(viewParam);
  if (viewParam && adminPage === "messages.php") openMessage(viewParam);

  window.EphAdmin = { openQuote: openQuote, openMessage: openMessage };

  /* =======================================================================
     4. WYSIWYG rich text editor
     ======================================================================= */
  function escapeHtml(s) {
    return String(s).replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;");
  }

  function plainToHtml(text) {
    return String(text).split(/\n{2,}/).map(function (part) {
      return "<p>" + escapeHtml(part).replace(/\n/g, "<br>") + "</p>";
    }).join("");
  }

  var EDITOR_TOOLS = [
    ["bold", '<i class="fa-solid fa-bold" aria-hidden="true"></i>', "Bold (Ctrl+B)"],
    ["italic", '<i class="fa-solid fa-italic" aria-hidden="true"></i>', "Italic (Ctrl+I)"],
    ["underline", '<i class="fa-solid fa-underline" aria-hidden="true"></i>', "Underline (Ctrl+U)"],
    ["strikeThrough", '<i class="fa-solid fa-strikethrough" aria-hidden="true"></i>', "Strikethrough"],
    ["sep"],
    ["h3", '<span class="editor-txt">H3</span>', "Heading"],
    ["h4", '<span class="editor-txt">H4</span>', "Subheading"],
    ["p", '<span class="editor-txt">P</span>', "Normal text"],
    ["sep"],
    ["insertUnorderedList", '<i class="fa-solid fa-list-ul" aria-hidden="true"></i>', "Bullet list"],
    ["insertOrderedList", '<i class="fa-solid fa-list-ol" aria-hidden="true"></i>', "Numbered list"],
    ["quote", '<i class="fa-solid fa-quote-left" aria-hidden="true"></i>', "Quote"],
    ["sep"],
    ["link", '<i class="fa-solid fa-link" aria-hidden="true"></i>', "Insert link"],
    ["unlink", '<i class="fa-solid fa-link-slash" aria-hidden="true"></i>', "Remove link"],
    ["removeFormat", '<i class="fa-solid fa-eraser" aria-hidden="true"></i>', "Clear formatting"],
    ["sep"],
    ["undo", '<i class="fa-solid fa-rotate-left" aria-hidden="true"></i>', "Undo (Ctrl+Z)"],
    ["redo", '<i class="fa-solid fa-rotate-right" aria-hidden="true"></i>', "Redo (Ctrl+Y)"]
  ];

  function exec(command, value) {
    try {
      document.execCommand(command, false, value || null);
    } catch (err) { /* unsupported command: ignore */ }
  }

  function initEditor(ta) {
    if (ta.dataset.editorReady) return;
    ta.dataset.editorReady = "1";

    exec("defaultParagraphSeparator", "p");

    var wrap = el("div", "editor");
    var toolbar = el("div", "editor-toolbar");
    var area = el("div", "editor-area");
    area.contentEditable = "true";

    var group = ta.closest(".form-group");
    var label = group ? group.querySelector("label") : null;
    area.dataset.placeholder = ta.dataset.placeholder || (label ? label.textContent : "") || "Start writing…";

    EDITOR_TOOLS.forEach(function (t) {
      if (t[0] === "sep") {
        toolbar.appendChild(el("span", "editor-sep"));
        return;
      }
      var b = document.createElement("button");
      b.type = "button";
      b.className = "editor-btn";
      b.title = t[2];
      b.setAttribute("aria-label", t[2]);
      b.innerHTML = t[1];
      b.addEventListener("mousedown", function (e) { e.preventDefault(); }); /* keep selection */
      b.addEventListener("click", function () {
        area.focus();
        var cmd = t[0];
        if (cmd === "h3" || cmd === "h4" || cmd === "p") {
          exec("formatBlock", "<" + cmd + ">");
        } else if (cmd === "quote") {
          exec("formatBlock", "<blockquote>");
        } else if (cmd === "link") {
          var url = window.prompt("Link URL (https://…):", "https://");
          if (url) exec("createLink", url);
        } else {
          exec(cmd);
        }
        sync();
      });
      toolbar.appendChild(b);
    });

    /* Existing plain text (legacy records) is loaded as paragraphs. */
    var initial = ta.value;
    area.innerHTML = initial && !/<[a-z][^>]*>/i.test(initial) ? plainToHtml(initial) : initial;

    function sync() { ta.value = area.innerHTML; }
    area.addEventListener("input", sync);
    area.addEventListener("blur", sync);

    ta.parentNode.insertBefore(wrap, ta);
    wrap.appendChild(toolbar);
    wrap.appendChild(area);
    ta.hidden = true;
    sync();
  }

  document.querySelectorAll("textarea.js-editor").forEach(initEditor);

  /* =======================================================================
     5. Auto slug helper
     ======================================================================= */
  var titleField = document.getElementById("title");
  var slugField = document.getElementById("slug");
  if (titleField && slugField) {
    titleField.addEventListener("input", function () {
      if (!slugField.dataset.touched) {
        slugField.value = titleField.value.toLowerCase().replace(/[^a-z0-9]+/g, "-").replace(/^-|-$/g, "");
      }
    });
  }
  if (slugField) {
    slugField.addEventListener("input", function () { slugField.dataset.touched = "1"; });
  }
})();
