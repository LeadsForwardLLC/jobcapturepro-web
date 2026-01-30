<?php
/**
 * Survey Step 1: Business name + Business type
 *
 * @package JCP_Core
 */
?>
<section class="survey-step active" data-step="0">
  <div class="survey-head">
    <div class="survey-eyebrow">Online Demo</div>
    <h1 class="survey-title">See a live demo built for your business</h1>
    <p class="survey-subtitle">
      Tell us a few details so the demo reflects real jobs and real outcomes.
    </p>
    <div class="survey-note">
      <svg class="survey-note-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <rect x="2" y="4" width="20" height="14" rx="2" ry="2"></rect>
        <line x1="2" y1="20" x2="22" y2="20"></line>
        <line x1="16" y1="4" x2="16" y2="2"></line>
        <line x1="8" y1="4" x2="8" y2="2"></line>
      </svg>
      <strong>Best viewed on desktop or laptop.</strong>
    </div>
  </div>

  <form class="survey-form" autocomplete="off">
    <div class="survey-field">
      <label for="businessName">Business name</label>
      <input
        id="businessName"
        type="text"
        class="survey-input"
        placeholder="Summit Plumbing"
        required
      />
    </div>

    <div class="survey-field">
      <label for="niche">Business type</label>
      <select id="niche" class="survey-input" required>
        <option value="">Select your business type</option>
        <optgroup label="Building & mechanical">
          <option value="plumbing">Plumbing</option>
          <option value="hvac">HVAC</option>
          <option value="electrical">Electrical</option>
          <option value="roofing">Roofing</option>
        </optgroup>
        <optgroup label="General contracting & remodeling">
          <option value="general-contractor">General Contractor</option>
          <option value="handyman">Handyman</option>
          <option value="remodeling">Remodeling / Renovation</option>
        </optgroup>
        <optgroup label="Outdoor & property">
          <option value="landscaping">Landscaping</option>
          <option value="lawn-care">Lawn care</option>
          <option value="tree-service">Tree service</option>
          <option value="pest-control">Pest control</option>
          <option value="fencing">Fencing</option>
        </optgroup>
        <optgroup label="Cleaning & restoration">
          <option value="carpet-cleaning">Carpet cleaning</option>
          <option value="house-cleaning">House cleaning</option>
          <option value="pressure-washing">Pressure washing</option>
          <option value="painting">Painting (interior / exterior)</option>
        </optgroup>
        <optgroup label="Other trades">
          <option value="flooring">Flooring</option>
          <option value="windows-doors">Windows & doors</option>
          <option value="insulation">Insulation</option>
          <option value="garage-doors">Garage doors</option>
          <option value="pool-service">Pool service</option>
          <option value="moving-junk">Moving / Junk removal</option>
        </optgroup>
        <optgroup label="Other">
          <option value="other">Other home service</option>
        </optgroup>
      </select>
    </div>
  </form>

  <div class="survey-actions-row">
    <button class="survey-btn" data-action="next">Next step</button>
  </div>
</section>
