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
    <h1 class="survey-title">Launch a live demo built for your business</h1>
    <p class="survey-subtitle">
      Tell us your context so the demo reflects real jobs and real outcomes.
    </p>
    <div class="survey-note">
      <strong>Best experience on desktop or laptop.</strong>
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
    <button class="survey-btn" data-action="next">Continue</button>
  </div>
</section>
