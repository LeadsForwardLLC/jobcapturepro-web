/**
 * Contact page – hero + form (first name, last name, email, phone optional, message, file upload)
 * Uses same UI patterns as early-access and survey; file drop zone supports any type up to 20MB.
 *
 * @package JCP_Core
 */

(function () {
  const MAX_FILE_SIZE = 20 * 1024 * 1024; // 20MB
  const MAX_FILE_SIZE_LABEL = '20MB';

  function escAttr(str) {
    if (str == null) return '';
    return String(str)
      .replace(/&/g, '&amp;')
      .replace(/"/g, '&quot;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/`/g, '&#96;');
  }

  window.renderContact = function () {
    const root = document.getElementById('jcp-app');
    if (!root) return;

    const pageTitle = (root.dataset.pageTitle || '').trim();
    const pageSupporting = (root.dataset.pageSupporting || '').trim();
    const headline = pageTitle || 'Need help or have a question?';
    const subhead = pageSupporting || "We're happy to help with setup, troubleshooting, or just to hear your feedback. Fill out the form below, and someone from the team will get back to you within one business day.";

    root.innerHTML = `
      <main class="jcp-marketing jcp-contact-page">
        <section class="jcp-section rankings-section">
          <div class="jcp-container">
            <div class="rankings-header">
              <h1>${escAttr(headline)}</h1>
              <p class="rankings-subtitle">
                ${escAttr(subhead)}
              </p>
            </div>
          </div>
        </section>

        <section class="jcp-section jcp-form-section">
          <div class="jcp-container">
            <div class="jcp-form-wrapper">
              <form class="jcp-contact-form" id="contactForm" novalidate>
                <div class="jcp-form-error" id="contactFormError" role="alert" aria-live="polite" style="display: none;"></div>
                <div class="jcp-form-grid">
                  <div class="jcp-form-field">
                    <label for="contact-first-name">First name</label>
                    <input
                      id="contact-first-name"
                      type="text"
                      name="first_name"
                      placeholder="John"
                      required
                    />
                  </div>
                  <div class="jcp-form-field">
                    <label for="contact-last-name">Last name</label>
                    <input
                      id="contact-last-name"
                      type="text"
                      name="last_name"
                      placeholder="Smith"
                      required
                    />
                  </div>
                  <div class="jcp-form-field">
                    <label for="contact-email">Email address</label>
                    <input
                      type="email"
                      id="contact-email"
                      name="email"
                      placeholder="you@company.com"
                      required
                    />
                  </div>
                  <div class="jcp-form-field">
                    <label for="contact-phone">Phone <span class="jcp-form-optional">(optional)</span></label>
                    <input
                      type="tel"
                      id="contact-phone"
                      name="phone"
                      placeholder="(555) 123-4567"
                    />
                  </div>
                </div>
                <div class="jcp-form-field jcp-form-field-full">
                  <label for="contact-topic">Topic</label>
                  <select id="contact-topic" name="topic" required>
                    <option value="">Select...</option>
                    <option value="getting-started">Getting Started</option>
                    <option value="technical-issue">Technical Issue</option>
                    <option value="feature-request">Feature Request</option>
                    <option value="billing">Billing</option>
                    <option value="general-question">General Question</option>
                  </select>
                </div>
                <div class="jcp-form-field jcp-form-field-full">
                  <label for="contact-message">Message</label>
                  <textarea
                    id="contact-message"
                    name="message"
                    placeholder="How can we help?"
                    rows="5"
                    required
                  ></textarea>
                </div>
                <div class="jcp-form-field jcp-form-field-full jcp-file-drop-wrapper">
                  <label>Attach a file <span class="jcp-form-optional">(optional, any type, max ${MAX_FILE_SIZE_LABEL})</span></label>
                  <div class="jcp-file-drop" id="contactFileDrop" role="button" tabindex="0" aria-label="Drop file here or click to browse">
                    <input type="file" id="contactFileInput" name="attachment" accept="" hidden aria-hidden="true" />
                    <span class="jcp-file-drop-text" id="contactFileDropText">Drag and drop a file here, or click to browse</span>
                    <span class="jcp-file-drop-name" id="contactFileDropName" style="display: none;"></span>
                    <button type="button" class="jcp-file-drop-remove" id="contactFileDropRemove" style="display: none;" aria-label="Remove file">Remove</button>
                  </div>
                  <p class="jcp-form-field-helper" id="contactFileError" style="display: none;"></p>
                </div>
                <div class="jcp-form-actions">
                  <button type="submit" class="btn btn-primary" id="contactSubmitBtn">
                    Send message
                  </button>
                </div>
              </form>
            </div>
          </div>
        </section>

        <section class="jcp-section rankings-section">
          <div class="jcp-container">
            <div class="rankings-cta">
              <div class="cta-content">
                <h3>See how it works</h3>
                <p class="cta-paragraph">Preview how JobCapturePro would publish your work across Google, your website, reviews, and the public directory.</p>
              </div>
              <div class="cta-button-wrapper">
                <a class="btn btn-primary rankings-cta-btn" href="/demo">See your business in the live demo</a>
                <p class="cta-note">No signup required. Takes two minutes.</p>
              </div>
            </div>
          </div>
        </section>
      </main>
    `;

    initContactForm();
    initFileDrop();
  };

  function showError(elId, message) {
    const el = document.getElementById(elId);
    if (!el) return;
    el.textContent = message || '';
    el.style.display = message ? 'block' : 'none';
  }

  function initContactForm() {
    const form = document.getElementById('contactForm');
    const errorEl = document.getElementById('contactFormError');
    if (!form || !errorEl) return;

    form.addEventListener('submit', function (e) {
      e.preventDefault();
      errorEl.style.display = 'none';
      errorEl.textContent = '';
      errorEl.removeAttribute('data-success');

      const first = form.querySelector('[name="first_name"]');
      const last = form.querySelector('[name="last_name"]');
      const email = form.querySelector('[name="email"]');
      const topic = form.querySelector('[name="topic"]');
      const message = form.querySelector('[name="message"]');
      if (!first || !last || !email || !topic || !message) return;

      const firstVal = (first.value || '').trim();
      const lastVal = (last.value || '').trim();
      const emailVal = (email.value || '').trim();
      const topicVal = (topic.value || '').trim();
      const messageVal = (message.value || '').trim();

      if (!firstVal) {
        errorEl.textContent = 'Please enter your first name.';
        errorEl.style.display = 'block';
        first.focus();
        return;
      }
      if (!lastVal) {
        errorEl.textContent = 'Please enter your last name.';
        errorEl.style.display = 'block';
        last.focus();
        return;
      }
      if (!emailVal) {
        errorEl.textContent = 'Please enter your email address.';
        errorEl.style.display = 'block';
        email.focus();
        return;
      }
      if (!topicVal) {
        errorEl.textContent = 'Please select a topic.';
        errorEl.style.display = 'block';
        topic.focus();
        return;
      }
      if (!messageVal) {
        errorEl.textContent = 'Please enter your message.';
        errorEl.style.display = 'block';
        message.focus();
        return;
      }

      const submitBtn = document.getElementById('contactSubmitBtn');
      const phoneEl = form.querySelector('[name="phone"]');
      const phoneVal = (phoneEl && phoneEl.value) ? (phoneEl.value || '').trim() : '';
      const fileInput = document.getElementById('contactFileInput');
      const file = fileInput && fileInput.files && fileInput.files[0] ? fileInput.files[0] : null;

      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.textContent = 'Sending…';
      }

      function buildPayload(attachmentFilename, attachmentData) {
        var payload = {
          first_name: firstVal,
          last_name: lastVal,
          email: emailVal,
          phone: phoneVal,
          topic: topicVal,
          message: messageVal,
        };
        if (attachmentFilename && attachmentData) {
          payload.attachment_filename = attachmentFilename;
          payload.attachment_data = attachmentData;
        }
        return payload;
      }

      function doSend(payload, omitAttachmentMessage) {
        var config = window.JCP_CONTACT_FORM || {};
        var baseUrl = (window.JCP_CONFIG && window.JCP_CONFIG.baseUrl) ? window.JCP_CONFIG.baseUrl.replace(/\/?$/, '') : '';
        var restUrl = config.rest_url || baseUrl + '/wp-json/jcp/v1/contact-submit';

        fetch(restUrl, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload),
        })
          .then(function (res) {
            var ok = res.status === 200 || res.status === 201;
            return res.text().then(function (text) {
              var data = {};
              try {
                data = JSON.parse(text);
              } catch (e) {
                data = { message: text || '' };
              }
              var msg = (data.message && (data.message + '').toLowerCase()) || '';
              var isServerSizeError = res.status === 413 ||
                (res.status === 400 && (
                  msg.indexOf('entity too large') !== -1 ||
                  msg.indexOf('invalid json') !== -1 ||
                  msg.indexOf('missing parameter') !== -1 ||
                  msg.indexOf('required') !== -1
                ));
              if (isServerSizeError) {
                data._entityTooLarge = true;
              }
              return { ok: ok, data: data };
            }).catch(function () {
              return { ok: ok, data: {} };
            });
          })
          .then(function (result) {
            if (result.ok) {
              try {
                if (typeof _paq !== 'undefined') {
                  var formData = { first_name: firstVal, last_name: lastVal, email: emailVal, topic: topicVal, message: messageVal };
                  if (phoneVal) formData.phone = phoneVal;
                  _paq.push(['trackEvent', 'Contact Submitted', 'Submitted', JSON.stringify(formData)]);
                }
              } catch (err) {}
              var config = window.JCP_CONTACT_FORM || {};
              var redirect = config.success_redirect || '/contact-success';
              if (result.data && result.data.attachment_omitted) {
                redirect += (redirect.indexOf('?') !== -1 ? '&' : '?') + 'attachment_omitted=1';
              }
              window.location.href = redirect;
              return;
            } else if (result.data._entityTooLarge && payload.attachment_data && !omitAttachmentMessage) {
              doSend(buildPayload(null, null), true);
              return;
            } else {
              errorEl.textContent = result.data.message || 'Something went wrong. Please try again.';
              errorEl.style.display = 'block';
            }
            if (submitBtn) {
              submitBtn.disabled = false;
              submitBtn.textContent = 'Send message';
            }
          })
          .catch(function () {
            if (submitBtn) {
              submitBtn.disabled = false;
              submitBtn.textContent = 'Send message';
            }
            errorEl.textContent = 'Something went wrong. Please try again.';
            errorEl.style.display = 'block';
          });
      }

      if (file) {
        var reader = new FileReader();
        reader.onload = function () {
          var dataUrl = reader.result;
          var base64 = (dataUrl && dataUrl.indexOf('base64,') >= 0) ? dataUrl.split('base64,')[1] : dataUrl;
          if (!base64) {
            if (submitBtn) { submitBtn.disabled = false; submitBtn.textContent = 'Send message'; }
            errorEl.textContent = 'Could not read file. Please try again.';
            errorEl.style.display = 'block';
            return;
          }
          doSend(buildPayload(file.name, base64));
        };
        reader.onerror = function () {
          if (submitBtn) { submitBtn.disabled = false; submitBtn.textContent = 'Send message'; }
          errorEl.textContent = 'Could not read file. Please try again.';
          errorEl.style.display = 'block';
        };
        reader.readAsDataURL(file);
      } else {
        doSend(buildPayload(null, null));
      }
    });
  }

  function clearFileDrop() {
    const input = document.getElementById('contactFileInput');
    const text = document.getElementById('contactFileDropText');
    const name = document.getElementById('contactFileDropName');
    const remove = document.getElementById('contactFileDropRemove');
    const err = document.getElementById('contactFileError');
    if (input) input.value = '';
    if (text) text.style.display = '';
    if (name) {
      name.style.display = 'none';
      name.textContent = '';
    }
    if (remove) remove.style.display = 'none';
    if (err) {
      err.style.display = 'none';
      err.textContent = '';
    }
  }

  function initFileDrop() {
    const drop = document.getElementById('contactFileDrop');
    const input = document.getElementById('contactFileInput');
    const text = document.getElementById('contactFileDropText');
    const nameEl = document.getElementById('contactFileDropName');
    const removeBtn = document.getElementById('contactFileDropRemove');
    const errEl = document.getElementById('contactFileError');

    if (!drop || !input || !text || !nameEl || !removeBtn || !errEl) return;

    function setFile(file) {
      errEl.style.display = 'none';
      errEl.textContent = '';
      if (!file) {
        input.value = '';
        text.style.display = '';
        nameEl.style.display = 'none';
        nameEl.textContent = '';
        removeBtn.style.display = 'none';
        drop.classList.remove('jcp-file-drop-has-file');
        return;
      }
      if (file.size > MAX_FILE_SIZE) {
        errEl.textContent = 'File must be under ' + MAX_FILE_SIZE_LABEL + '.';
        errEl.style.display = 'block';
        return;
      }
      input.files = null;
      const dt = new DataTransfer();
      dt.items.add(file);
      input.files = dt.files;
      text.style.display = 'none';
      nameEl.textContent = file.name;
      nameEl.style.display = '';
      removeBtn.style.display = '';
      drop.classList.add('jcp-file-drop-has-file');
    }

    function prevent(e) {
      e.preventDefault();
      e.stopPropagation();
    }

    drop.addEventListener('click', function (e) {
      if (e.target === removeBtn) return;
      input.click();
    });

    drop.addEventListener('keydown', function (e) {
      if (e.key !== 'Enter' && e.key !== ' ') return;
      e.preventDefault();
      if (e.target === removeBtn) return;
      input.click();
    });

    removeBtn.addEventListener('click', function (e) {
      e.preventDefault();
      e.stopPropagation();
      setFile(null);
    });

    input.addEventListener('change', function () {
      const file = input.files && input.files[0];
      setFile(file || null);
    });

    drop.addEventListener('dragover', prevent);
    drop.addEventListener('dragenter', function (e) {
      prevent(e);
      drop.classList.add('jcp-file-drop-dragover');
    });
    drop.addEventListener('dragleave', function (e) {
      prevent(e);
      drop.classList.remove('jcp-file-drop-dragover');
    });
    drop.addEventListener('drop', function (e) {
      prevent(e);
      drop.classList.remove('jcp-file-drop-dragover');
      const file = e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files[0];
      setFile(file || null);
    });
  }
})();
