<?php
/**
 * Template Name: Design System (Internal)
 *
 * INTERNAL-ONLY page: single source of truth for JobCapturePro UI components,
 * spacing, typography, and patterns. Not linked in navigation; NOINDEX/NOFOLLOW.
 * URL: /design-system (accessible only to logged-in users and developers).
 *
 * @package JCP_Core
 */

get_header(); 
?>

<style>
	/* Prevent indexing */
	body.page-design-system {
		--design-system-page: true;
	}
</style>

<div id="jcp-design-system-page" class="jcp-design-system-page">
	
	<!-- ============================================================
	     HEADER SECTION
	     ============================================================ -->
	<section class="ds-section ds-intro">
		<div class="jcp-container">
			<h1>Design System</h1>
			<p class="jcp-text-muted">Single source of truth for JobCapturePro UI components, spacing, typography, and patterns</p>
			<p class="jcp-text-sm"><strong>Status:</strong> Internal documentation • Not indexed • Developers only</p>
		</div>
	</section>

	<!-- ============================================================
	     SPACING & LAYOUT SYSTEM
	     ============================================================ -->
	<section class="ds-section">
		<div class="jcp-container">
			<h2>Spacing & Layout System</h2>
			
			<div class="ds-subsection">
				<h3>Container Width</h3>
				<p>Standard container width used across all pages: <code>min(1240px, 94%)</code></p>
				<div class="ds-demo" style="background: #f3f4f6; border: 2px dashed #d1d5db; margin: var(--jcp-space-lg) 0;">
					<div class="jcp-container" style="background: #fff; border: 2px solid #e5e7eb; padding: var(--jcp-space-lg);">
						<p style="margin: 0; text-align: center; color: #6b7280;">Container: 1240px max (or 94% viewport)</p>
					</div>
				</div>
			</div>

			<div class="ds-subsection">
				<h3>Spacing Scale (8px-based)</h3>
				<p>All margins, paddings, and gaps follow this 8px scale:</p>
				<div class="ds-spacing-grid">
					<div class="ds-spacing-item">
						<div class="ds-spacing-box" style="width: var(--jcp-space-xs); height: 40px; background: #ff5036;"></div>
						<code>--jcp-space-xs: 4px</code>
					</div>
					<div class="ds-spacing-item">
						<div class="ds-spacing-box" style="width: var(--jcp-space-sm); height: 40px; background: #ff5036;"></div>
						<code>--jcp-space-sm: 8px</code>
					</div>
					<div class="ds-spacing-item">
						<div class="ds-spacing-box" style="width: var(--jcp-space-md); height: 40px; background: #ff5036;"></div>
						<code>--jcp-space-md: 16px</code>
					</div>
					<div class="ds-spacing-item">
						<div class="ds-spacing-box" style="width: var(--jcp-space-lg); height: 40px; background: #ff5036;"></div>
						<code>--jcp-space-lg: 24px</code>
					</div>
					<div class="ds-spacing-item">
						<div class="ds-spacing-box" style="width: var(--jcp-space-xl); height: 40px; background: #ff5036;"></div>
						<code>--jcp-space-xl: 32px</code>
					</div>
					<div class="ds-spacing-item">
						<div class="ds-spacing-box" style="width: var(--jcp-space-2xl); height: 40px; background: #ff5036;"></div>
						<code>--jcp-space-2xl: 40px</code>
					</div>
					<div class="ds-spacing-item">
						<div class="ds-spacing-box" style="width: var(--jcp-space-3xl); height: 40px; background: #ff5036;"></div>
						<code>--jcp-space-3xl: 48px</code>
					</div>
					<div class="ds-spacing-item">
						<div class="ds-spacing-box" style="width: var(--jcp-space-5xl); height: 40px; background: #ff5036;"></div>
						<code>--jcp-space-5xl: 64px</code>
					</div>
					<div class="ds-spacing-item">
						<div class="ds-spacing-box" style="width: var(--jcp-space-6xl); height: 40px; background: #ff5036;"></div>
						<code>--jcp-space-6xl: 80px</code>
					</div>
				</div>
			</div>

			<div class="ds-subsection">
				<h3>Section Vertical Rhythm</h3>
				<p>All major sections (hero, features, faq, etc.) use consistent top/bottom padding:</p>
				<ul>
					<li><strong>Standard section padding:</strong> <code>var(--jcp-space-5xl) 0</code> (64px top/bottom)</li>
					<li><strong>Mobile section padding:</strong> <code>var(--jcp-space-3xl) 0</code> (48px top/bottom)</li>
					<li><strong>Grid gaps between items:</strong> <code>var(--jcp-space-lg)</code> (24px)</li>
				</ul>
			</div>

			<div class="ds-subsection">
				<h3>Responsive Breakpoints</h3>
				<ul>
					<li><strong>Mobile:</strong> < 768px</li>
					<li><strong>Tablet:</strong> 768px - 1024px</li>
					<li><strong>Desktop:</strong> > 1024px</li>
					<li><strong>Large desktop:</strong> > 1280px</li>
				</ul>
			</div>
		</div>
	</section>

	<!-- ============================================================
	     COLOR SYSTEM
	     ============================================================ -->
	<section class="ds-section">
		<div class="jcp-container">
			<h2>Color Palette</h2>
			
			<div class="ds-subsection">
				<h3>Brand Colors</h3>
				<div class="ds-color-grid">
					<div class="ds-color-item">
						<div class="ds-color-box" style="background: var(--jcp-color-primary);"></div>
						<div class="ds-color-info">
							<strong>Primary</strong>
							<code>#ff5036</code>
						</div>
					</div>
					<div class="ds-color-item">
						<div class="ds-color-box" style="background: var(--jcp-color-secondary);"></div>
						<div class="ds-color-info">
							<strong>Secondary</strong>
							<code>#1f2937</code>
						</div>
					</div>
					<div class="ds-color-item">
						<div class="ds-color-box" style="background: var(--jcp-color-success);"></div>
						<div class="ds-color-info">
							<strong>Success</strong>
							<code>#10b981</code>
						</div>
					</div>
					<div class="ds-color-item">
						<div class="ds-color-box" style="background: var(--jcp-color-warning);"></div>
						<div class="ds-color-info">
							<strong>Warning</strong>
							<code>#f59e0b</code>
						</div>
					</div>
					<div class="ds-color-item">
						<div class="ds-color-box" style="background: var(--jcp-color-error);"></div>
						<div class="ds-color-info">
							<strong>Error</strong>
							<code>#ef4444</code>
						</div>
					</div>
					<div class="ds-color-item">
						<div class="ds-color-box" style="background: var(--jcp-color-info);"></div>
						<div class="ds-color-info">
							<strong>Info</strong>
							<code>#3b82f6</code>
						</div>
					</div>
				</div>
			</div>

			<div class="ds-subsection">
				<h3>Text Colors</h3>
				<ul>
					<li><strong>Primary text:</strong> <code>var(--jcp-color-text-primary)</code> - Default body text</li>
					<li><strong>Secondary text:</strong> <code>var(--jcp-color-text-secondary)</code> - Subtext, labels</li>
					<li><strong>Tertiary text:</strong> <code>var(--jcp-color-text-tertiary)</code> - Muted, helper text</li>
					<li><strong>Light text:</strong> <code>var(--jcp-color-text-light)</code> - On dark backgrounds</li>
				</ul>
			</div>

			<div class="ds-subsection">
				<h3>Background Colors</h3>
				<ul>
					<li><strong>Primary background:</strong> <code>var(--jcp-color-bg-primary)</code> - Main page background</li>
					<li><strong>Secondary background:</strong> <code>var(--jcp-color-bg-secondary)</code> - Cards, sections</li>
					<li><strong>Tertiary background:</strong> <code>var(--jcp-color-bg-tertiary)</code> - Hover states, light fills</li>
					<li><strong>Dark background:</strong> <code>var(--jcp-color-bg-dark)</code> - Dark sections</li>
				</ul>
			</div>
		</div>
	</section>

	<!-- ============================================================
	     TYPOGRAPHY SYSTEM
	     ============================================================ -->
	<section class="ds-section">
		<div class="jcp-container">
			<h2>Typography System</h2>
			
			<div class="ds-subsection">
				<h3>Font Families</h3>
				<ul>
					<li><strong>Primary (body):</strong> -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif</li>
					<li><strong>Heading:</strong> Same as primary</li>
					<li><strong>Monospace (code):</strong> "Courier New", monospace</li>
				</ul>
			</div>

			<div class="ds-subsection">
				<h3>Heading Hierarchy (H1 - H6)</h3>
				<div class="ds-typography-demo">
					<div class="ds-type-item">
						<h1>Heading 1 (H1)</h1>
						<p class="ds-type-meta">Font size: <code>var(--jcp-font-size-6xl)</code> (60px) • Weight: 800 • Line height: 1.2</p>
						<p class="ds-type-usage"><strong>Used for:</strong> Page titles, major section headers</p>
					</div>

					<div class="ds-type-item">
						<h2>Heading 2 (H2)</h2>
						<p class="ds-type-meta">Font size: <code>var(--jcp-font-size-5xl)</code> (48px) • Weight: 700 • Line height: 1.25</p>
						<p class="ds-type-usage"><strong>Used for:</strong> Section titles (Features, FAQ, How-It-Works)</p>
					</div>

					<div class="ds-type-item">
						<h3>Heading 3 (H3)</h3>
						<p class="ds-type-meta">Font size: <code>var(--jcp-font-size-2xl)</code> (24px) • Weight: 600 • Line height: 1.33</p>
						<p class="ds-type-usage"><strong>Used for:</strong> Subsection headers, card titles</p>
					</div>

					<div class="ds-type-item">
						<h4>Heading 4 (H4)</h4>
						<p class="ds-type-meta">Font size: <code>var(--jcp-font-size-xl)</code> (20px) • Weight: 600 • Line height: 1.4</p>
						<p class="ds-type-usage"><strong>Used for:</strong> Feature headers, list item headers</p>
					</div>

					<div class="ds-type-item">
						<h5>Heading 5 (H5)</h5>
						<p class="ds-type-meta">Font size: <code>var(--jcp-font-size-lg)</code> (18px) • Weight: 600 • Line height: 1.4</p>
						<p class="ds-type-usage"><strong>Used for:</strong> Minor headers, labels</p>
					</div>

					<div class="ds-type-item">
						<h6>Heading 6 (H6)</h6>
						<p class="ds-type-meta">Font size: <code>var(--jcp-font-size-base)</code> (16px) • Weight: 600 • Line height: 1.5</p>
						<p class="ds-type-usage"><strong>Used for:</strong> Small headers, eyebrows</p>
					</div>
				</div>
			</div>

			<div class="ds-subsection">
				<h3>Body Text Styles</h3>
				<div class="ds-typography-demo">
					<div class="ds-type-item">
						<p>This is body text (16px). Default paragraph styling for content.</p>
						<p class="ds-type-meta">Font size: <code>var(--jcp-font-size-base)</code> (16px) • Weight: 400 • Line height: 1.6</p>
					</div>

					<div class="ds-type-item">
						<p style="font-size: var(--jcp-font-size-sm);">This is small body text (14px). Used for subtext and descriptions.</p>
						<p class="ds-type-meta">Font size: <code>var(--jcp-font-size-sm)</code> (14px) • Weight: 400 • Line height: 1.6</p>
					</div>

					<div class="ds-type-item">
						<p class="jcp-text-muted">This is muted text. Used for secondary information and captions.</p>
						<p class="ds-type-meta">Font size: <code>var(--jcp-font-size-base)</code> (16px) • Weight: 400 • Color: --jcp-color-text-tertiary • Line height: 1.6</p>
					</div>
				</div>
			</div>

			<div class="ds-subsection">
				<h3>Font Weight Scale</h3>
				<ul>
					<li><strong>400:</strong> Normal weight (body text, regular content)</li>
					<li><strong>500:</strong> Medium weight (labels, slight emphasis)</li>
					<li><strong>600:</strong> Semibold (subheadings, card titles)</li>
					<li><strong>700:</strong> Bold (section headers, emphasis)</li>
					<li><strong>800:</strong> Extrabold (page titles, H1)</li>
				</ul>
			</div>
		</div>
	</section>

	<!-- ============================================================
	     BUTTONS
	     ============================================================ -->
	<section class="ds-section">
		<div class="jcp-container">
			<h2>Buttons & CTAs</h2>
			
			<div class="ds-subsection">
				<h3>Primary Button</h3>
				<p>Default action button. Orange background (#ff5036) with white text.</p>
				<div class="ds-demo">
					<button class="jcp-button jcp-button-primary">Primary Button</button>
					<button class="jcp-button jcp-button-primary" disabled>Disabled</button>
				</div>
				<p class="ds-type-meta">
					Background: var(--jcp-color-primary) • Text: white • Hover: Darker orange • Padding: var(--jcp-space-md) var(--jcp-space-lg)
				</p>
			</div>

			<div class="ds-subsection">
				<h3>Secondary Button</h3>
				<p>Alternative action button. Gray background with dark text.</p>
				<div class="ds-demo">
					<button class="jcp-button jcp-button-secondary">Secondary Button</button>
					<button class="jcp-button jcp-button-secondary" disabled>Disabled</button>
				</div>
				<p class="ds-type-meta">
					Background: var(--jcp-color-bg-tertiary) • Text: var(--jcp-color-text-primary) • Hover: Lighter gray
				</p>
			</div>

			<div class="ds-subsection">
				<h3>Ghost Button</h3>
				<p>Minimal button. Transparent background with border.</p>
				<div class="ds-demo">
					<button class="jcp-button jcp-button-ghost">Ghost Button</button>
					<button class="jcp-button jcp-button-ghost" disabled>Disabled</button>
				</div>
				<p class="ds-type-meta">
					Background: transparent • Border: 2px solid --jcp-color-text-primary • Hover: Light background
				</p>
			</div>

			<div class="ds-subsection">
				<h3>Button Sizes</h3>
				<div class="ds-demo">
					<button class="jcp-button jcp-button-primary jcp-button-sm">Small</button>
					<button class="jcp-button jcp-button-primary">Medium (default)</button>
					<button class="jcp-button jcp-button-primary jcp-button-lg">Large</button>
				</div>
				<p class="ds-type-meta">
					Small: 12px font, reduced padding | Medium: 14px font, standard padding | Large: 16px font, increased padding
				</p>
			</div>

			<div class="ds-subsection">
				<h3>Button States</h3>
				<div class="ds-demo">
					<button class="jcp-button jcp-button-primary">Default</button>
					<button class="jcp-button jcp-button-primary">:hover</button>
					<button class="jcp-button jcp-button-primary">:active</button>
					<button class="jcp-button jcp-button-primary" disabled>Disabled</button>
				</div>
				<p class="ds-type-meta">
					All button states have smooth transitions (200ms)
				</p>
			</div>

			<div class="ds-subsection">
				<h3>CTA on Dark Background</h3>
				<div class="ds-demo" style="background: #1f2937; padding: var(--jcp-space-lg); border-radius: 8px;">
					<button class="jcp-button jcp-button-primary">Primary on Dark</button>
					<button class="jcp-button jcp-button-secondary" style="background: white; color: #1f2937;">Secondary on Dark</button>
				</div>
				<p class="ds-type-meta">
					Primary buttons work on dark backgrounds. Secondary buttons can have light background with dark text.
				</p>
			</div>
		</div>
	</section>

	<!-- ============================================================
	     CARDS & COMPONENTS
	     ============================================================ -->
	<section class="ds-section">
		<div class="jcp-container">
			<h2>Cards & UI Components</h2>
			
			<div class="ds-subsection">
				<h3>Feature Card</h3>
				<p>Card component used in Features section and similar layouts.</p>
				<div class="ds-demo">
					<div class="jcp-card">
						<div style="width: 48px; height: 48px; background: var(--jcp-color-primary); border-radius: 8px; margin-bottom: var(--jcp-space-md); display: flex; align-items: center; justify-content: center;">
							<span style="color: white; font-size: 24px;">★</span>
						</div>
						<h3>Feature Title</h3>
						<p>This is a feature card description. It explains what this feature does and why it matters to users.</p>
					</div>
				</div>
				<p class="ds-type-meta">
					Background: white • Padding: var(--jcp-space-lg) • Border-radius: 8px • Box-shadow: subtle shadow
				</p>
			</div>

			<div class="ds-subsection">
				<h3>Directory Card</h3>
				<p>Card used in directory listings and company profiles.</p>
				<div class="ds-demo">
					<div class="jcp-card">
						<div style="background: linear-gradient(135deg, #ff5036, #f97316); height: 120px; border-radius: 6px 6px 0 0; margin: -var(--jcp-space-lg) -var(--jcp-space-lg) var(--jcp-space-lg) -var(--jcp-space-lg);"></div>
						<h3>Company Name</h3>
						<p class="jcp-text-sm">Industry • Location</p>
						<p>Brief description of the company and what they do.</p>
						<a href="#" style="color: var(--jcp-color-primary); text-decoration: none; font-weight: 600;">View Profile →</a>
					</div>
				</div>
			</div>

			<div class="ds-subsection">
				<h3>Info Box / Alert</h3>
				<p>Used for important notices, tips, and alerts.</p>
				<div class="ds-demo">
					<div style="background: #f0f9ff; border-left: 4px solid var(--jcp-color-info); padding: var(--jcp-space-md) var(--jcp-space-lg); border-radius: 4px; margin: var(--jcp-space-md) 0;">
						<strong>Info:</strong> This is an informational message.
					</div>
					<div style="background: #fef3c7; border-left: 4px solid var(--jcp-color-warning); padding: var(--jcp-space-md) var(--jcp-space-lg); border-radius: 4px; margin: var(--jcp-space-md) 0;">
						<strong>Warning:</strong> This is a warning message.
					</div>
					<div style="background: #fee2e2; border-left: 4px solid var(--jcp-color-error); padding: var(--jcp-space-md) var(--jcp-space-lg); border-radius: 4px; margin: var(--jcp-space-md) 0;">
						<strong>Error:</strong> This is an error message.
					</div>
				</div>
			</div>

			<div class="ds-subsection">
				<h3>Badge / Status Pill</h3>
				<p>Small inline badges and status indicators.</p>
				<div class="ds-demo">
					<span style="display: inline-block; background: var(--jcp-color-primary); color: white; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; margin-right: var(--jcp-space-sm);">Active</span>
					<span style="display: inline-block; background: #d1d5db; color: #374151; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; margin-right: var(--jcp-space-sm);">Pending</span>
					<span style="display: inline-block; background: #dbeafe; color: #1e40af; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600;">New</span>
				</div>
			</div>

			<div class="ds-subsection">
				<h3>Pricing Comparison</h3>
				<p>Standard SaaS feature comparison table used on the Pricing page to communicate plan capabilities.</p>
				<p class="ds-type-meta">
					<strong>Purpose:</strong> Help users quickly compare Starter, Scale, and Enterprise plans by showing which features are included (✅), limited (➖), or not available (❌) in each plan.
				</p>
				<p class="ds-type-meta">
					<strong>When to use:</strong> On pricing pages where users need to make a plan selection decision. The table should be scannable in under 5 seconds.
				</p>
				<p class="ds-type-meta">
					<strong>Visual communication:</strong> Each cell contains only an icon (check.svg for included, x.svg for not available). Feature names are short and factual. No descriptive text in plan columns.
				</p>
				<div class="ds-demo">
					<section class="jcp-section rankings-section">
						<div class="jcp-container">
							<div class="rankings-header">
								<h3>Pricing Comparison (Production Component)</h3>
								<p class="rankings-subtitle">Exact structure and markup as used on the live pricing page.</p>
							</div>
							<div class="jcp-compare-table">
								<div class="jcp-compare-row jcp-compare-head">
									<div>Feature</div>
									<div>Starter</div>
									<div>Scale</div>
									<div>Enterprise</div>
								</div>

								<!-- Capture -->
								<div class="jcp-compare-row jcp-compare-group">
									<div>Photo capture</div>
									<div><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/shared/assets/icons/lucide/check.svg" class="lucide-icon lucide-icon-xs" alt="Included"></div>
									<div><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/shared/assets/icons/lucide/check.svg" class="lucide-icon lucide-icon-xs" alt="Included"></div>
									<div><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/shared/assets/icons/lucide/check.svg" class="lucide-icon lucide-icon-xs" alt="Included"></div>
								</div>
								<div class="jcp-compare-row">
									<div>CRM integration</div>
									<div><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/shared/assets/icons/lucide/x.svg" class="lucide-icon lucide-icon-xs" alt="Not available"></div>
									<div><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/shared/assets/icons/lucide/check.svg" class="lucide-icon lucide-icon-xs" alt="Included"></div>
									<div><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/shared/assets/icons/lucide/check.svg" class="lucide-icon lucide-icon-xs" alt="Included"></div>
								</div>
								<div class="jcp-compare-row">
									<div>Multi-location capture</div>
									<div><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/shared/assets/icons/lucide/x.svg" class="lucide-icon lucide-icon-xs" alt="Not available"></div>
									<div><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/shared/assets/icons/lucide/x.svg" class="lucide-icon lucide-icon-xs" alt="Not available"></div>
									<div><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/shared/assets/icons/lucide/check.svg" class="lucide-icon lucide-icon-xs" alt="Included"></div>
								</div>

								<!-- Publish -->
								<div class="jcp-compare-row jcp-compare-group">
									<div>Website publishing</div>
									<div><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/shared/assets/icons/lucide/check.svg" class="lucide-icon lucide-icon-xs" alt="Included"></div>
									<div><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/shared/assets/icons/lucide/check.svg" class="lucide-icon lucide-icon-xs" alt="Included"></div>
									<div><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/shared/assets/icons/lucide/check.svg" class="lucide-icon lucide-icon-xs" alt="Included"></div>
								</div>
								<div class="jcp-compare-row">
									<div>Social publishing</div>
									<div><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/shared/assets/icons/lucide/x.svg" class="lucide-icon lucide-icon-xs" alt="Not available"></div>
									<div><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/shared/assets/icons/lucide/check.svg" class="lucide-icon lucide-icon-xs" alt="Included"></div>
									<div><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/shared/assets/icons/lucide/check.svg" class="lucide-icon lucide-icon-xs" alt="Included"></div>
								</div>
								<div class="jcp-compare-row">
									<div>Google Business Profile automation</div>
									<div><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/shared/assets/icons/lucide/x.svg" class="lucide-icon lucide-icon-xs" alt="Not available"></div>
									<div><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/shared/assets/icons/lucide/check.svg" class="lucide-icon lucide-icon-xs" alt="Included"></div>
									<div><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/shared/assets/icons/lucide/check.svg" class="lucide-icon lucide-icon-xs" alt="Included"></div>
								</div>
								<div class="jcp-compare-row">
									<div>White-label publishing</div>
									<div><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/shared/assets/icons/lucide/x.svg" class="lucide-icon lucide-icon-xs" alt="Not available"></div>
									<div><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/shared/assets/icons/lucide/x.svg" class="lucide-icon lucide-icon-xs" alt="Not available"></div>
									<div><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/shared/assets/icons/lucide/check.svg" class="lucide-icon lucide-icon-xs" alt="Included"></div>
								</div>

								<!-- Reviews -->
								<div class="jcp-compare-row jcp-compare-group">
									<div>Manual review requests</div>
									<div><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/shared/assets/icons/lucide/check.svg" class="lucide-icon lucide-icon-xs" alt="Included"></div>
									<div><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/shared/assets/icons/lucide/check.svg" class="lucide-icon lucide-icon-xs" alt="Included"></div>
									<div><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/shared/assets/icons/lucide/check.svg" class="lucide-icon lucide-icon-xs" alt="Included"></div>
								</div>
								<div class="jcp-compare-row">
									<div>Automated review sequences</div>
									<div><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/shared/assets/icons/lucide/x.svg" class="lucide-icon lucide-icon-xs" alt="Not available"></div>
									<div><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/shared/assets/icons/lucide/check.svg" class="lucide-icon lucide-icon-xs" alt="Included"></div>
									<div><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/shared/assets/icons/lucide/check.svg" class="lucide-icon lucide-icon-xs" alt="Included"></div>
								</div>
								<div class="jcp-compare-row">
									<div>Custom review workflows</div>
									<div><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/shared/assets/icons/lucide/x.svg" class="lucide-icon lucide-icon-xs" alt="Not available"></div>
									<div><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/shared/assets/icons/lucide/x.svg" class="lucide-icon lucide-icon-xs" alt="Not available"></div>
									<div><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/shared/assets/icons/lucide/check.svg" class="lucide-icon lucide-icon-xs" alt="Included"></div>
								</div>

								<!-- Visibility -->
								<div class="jcp-compare-row jcp-compare-group">
									<div>Directory listings</div>
									<div><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/shared/assets/icons/lucide/check.svg" class="lucide-icon lucide-icon-xs" alt="Included"></div>
									<div><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/shared/assets/icons/lucide/check.svg" class="lucide-icon lucide-icon-xs" alt="Included"></div>
									<div><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/shared/assets/icons/lucide/check.svg" class="lucide-icon lucide-icon-xs" alt="Included"></div>
								</div>
								<div class="jcp-compare-row">
									<div>Job map visibility</div>
									<div><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/shared/assets/icons/lucide/x.svg" class="lucide-icon lucide-icon-xs" alt="Not available"></div>
									<div><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/shared/assets/icons/lucide/check.svg" class="lucide-icon lucide-icon-xs" alt="Included"></div>
									<div><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/shared/assets/icons/lucide/check.svg" class="lucide-icon lucide-icon-xs" alt="Included"></div>
								</div>
								<div class="jcp-compare-row">
									<div>Rank tracking</div>
									<div><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/shared/assets/icons/lucide/x.svg" class="lucide-icon lucide-icon-xs" alt="Not available"></div>
									<div><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/shared/assets/icons/lucide/check.svg" class="lucide-icon lucide-icon-xs" alt="Included"></div>
									<div><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/shared/assets/icons/lucide/check.svg" class="lucide-icon lucide-icon-xs" alt="Included"></div>
								</div>
								<div class="jcp-compare-row">
									<div>Advanced visibility reporting</div>
									<div><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/shared/assets/icons/lucide/x.svg" class="lucide-icon lucide-icon-xs" alt="Not available"></div>
									<div><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/shared/assets/icons/lucide/x.svg" class="lucide-icon lucide-icon-xs" alt="Not available"></div>
									<div><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/shared/assets/icons/lucide/check.svg" class="lucide-icon lucide-icon-xs" alt="Included"></div>
								</div>

								<!-- Reporting -->
								<div class="jcp-compare-row jcp-compare-group">
									<div>Email summaries</div>
									<div><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/shared/assets/icons/lucide/check.svg" class="lucide-icon lucide-icon-xs" alt="Included"></div>
									<div><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/shared/assets/icons/lucide/check.svg" class="lucide-icon lucide-icon-xs" alt="Included"></div>
									<div><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/shared/assets/icons/lucide/check.svg" class="lucide-icon lucide-icon-xs" alt="Included"></div>
								</div>
								<div class="jcp-compare-row">
									<div>Local reporting dashboards</div>
									<div><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/shared/assets/icons/lucide/x.svg" class="lucide-icon lucide-icon-xs" alt="Not available"></div>
									<div><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/shared/assets/icons/lucide/check.svg" class="lucide-icon lucide-icon-xs" alt="Included"></div>
									<div><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/shared/assets/icons/lucide/check.svg" class="lucide-icon lucide-icon-xs" alt="Included"></div>
								</div>
								<div class="jcp-compare-row">
									<div>Multi-location dashboards</div>
									<div><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/shared/assets/icons/lucide/x.svg" class="lucide-icon lucide-icon-xs" alt="Not available"></div>
									<div><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/shared/assets/icons/lucide/x.svg" class="lucide-icon lucide-icon-xs" alt="Not available"></div>
									<div><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/shared/assets/icons/lucide/check.svg" class="lucide-icon lucide-icon-xs" alt="Included"></div>
								</div>
							</div>
						</div>
					</section>
				</div>
			</div>
		</div>
	</section>

	<!-- ============================================================
	     NAVIGATION & HEADER
	     ============================================================ -->
	<section class="ds-section">
		<div class="jcp-container">
			<h2>Navigation & Header</h2>
			
			<div class="ds-subsection">
				<h3>Standard Navigation (Desktop)</h3>
				<p>Default header navigation used across all pages.</p>
				<div class="ds-demo">
					<nav style="display: flex; gap: var(--jcp-space-lg); padding: var(--jcp-space-md) 0; border-bottom: 1px solid #e5e7eb;">
						<a href="#" style="color: var(--jcp-color-text-primary); text-decoration: none; font-weight: 500;">Home</a>
						<a href="#" style="color: var(--jcp-color-text-secondary); text-decoration: none; font-weight: 500;">Features</a>
						<a href="#" style="color: var(--jcp-color-text-secondary); text-decoration: none; font-weight: 500;">Pricing</a>
						<a href="#" style="color: var(--jcp-color-text-secondary); text-decoration: none; font-weight: 500;">Directory</a>
						<a href="#" style="color: var(--jcp-color-primary); text-decoration: none; font-weight: 600;">Sign In</a>
					</nav>
				</div>
				<p class="ds-type-meta">
					Active link: Dark text with no underline • Inactive link: Lighter text • Hover: Primary color
				</p>
			</div>

			<div class="ds-subsection">
				<h3>Mobile Navigation</h3>
				<p>Hamburger menu for mobile devices (< 768px).</p>
				<p class="ds-type-meta">
					Icon: 24px hamburger • Positioning: Top-right corner • Animation: Smooth slide-in menu from right
				</p>
			</div>

			<div class="ds-subsection">
				<h3>Sticky Navigation</h3>
				<p>Navigation remains visible when scrolling past hero section.</p>
				<p class="ds-type-meta">
					Position: fixed • Top: 0 • Z-index: 100 • Shadow: subtle shadow when sticky • Transition: 300ms
				</p>
			</div>
		</div>
	</section>

	<!-- ============================================================
	     SECTIONS
	     ============================================================ -->
	<section class="ds-section">
		<div class="jcp-container">
			<h2>Section Components</h2>
			<p>All major page sections follow consistent spacing and structure.</p>

			<div class="ds-subsection">
				<h3>Hero Section</h3>
				<p>Large banner at top of page with headline, subheadline, and CTA.</p>
				<ul>
					<li><strong>Padding:</strong> var(--jcp-space-5xl) 0 (64px top/bottom)</li>
					<li><strong>Layout:</strong> Grid with image + content OR full-width background</li>
					<li><strong>Headline:</strong> H1 or large H2 (48-60px)</li>
					<li><strong>Subheadline:</strong> Body text or small heading (16-20px)</li>
					<li><strong>CTA:</strong> Primary button with optional secondary button</li>
				</ul>
			</div>

			<div class="ds-subsection">
				<h3>How-It-Works Section</h3>
				<p>4-step process grid showing how the platform works.</p>
				<ul>
					<li><strong>Layout:</strong> 4 columns on desktop, 2 columns on tablet, 1 column on mobile</li>
					<li><strong>Step card:</strong> Number badge + title + description</li>
					<li><strong>Spacing:</strong> var(--jcp-space-lg) gap between cards (24px)</li>
					<li><strong>Number badge:</strong> 48px circle with centered number</li>
				</ul>
			</div>

			<div class="ds-subsection">
				<h3>Features Section</h3>
				<p>Grid of feature cards highlighting key capabilities.</p>
				<ul>
					<li><strong>Layout:</strong> 3 columns on desktop, 2 columns on tablet, 1 column on mobile</li>
					<li><strong>Card:</strong> Icon + title + description</li>
					<li><strong>Icon:</strong> 48px square with SVG or emoji</li>
					<li><strong>Spacing:</strong> var(--jcp-space-lg) gap (24px)</li>
				</ul>
			</div>

			<div class="ds-subsection">
				<h3>FAQ Section</h3>
				<p>Accordion-style frequently asked questions.</p>
				<ul>
					<li><strong>Layout:</strong> Single column list</li>
					<li><strong>Item:</strong> Question (clickable) + answer (hidden until clicked)</li>
					<li><strong>Animation:</strong> Smooth expand/collapse (300ms)</li>
					<li><strong>Active state:</strong> Question text bold, background tinted</li>
				</ul>
			</div>

			<div class="ds-subsection">
				<h3>CTA Section</h3>
				<p>Final call-to-action band with headline and button.</p>
				<ul>
					<li><strong>Background:</strong> Dark (primary or secondary)</li>
					<li><strong>Text:</strong> White text with center alignment</li>
					<li><strong>Button:</strong> Primary orange button with white text</li>
					<li><strong>Padding:</strong> var(--jcp-space-5xl) 0 (64px top/bottom)</li>
				</ul>
			</div>

			<div class="ds-subsection">
				<h3>Early Access Page</h3>
				<p>Simple page for users who want early access before public launch.</p>
				<p class="ds-type-meta">
					<strong>Purpose:</strong> Collect sign-ups for early-bird pricing. Minimal, focused page that gets straight to the point.
				</p>
				<p class="ds-type-meta">
					<strong>How it differs from pricing:</strong> Pricing page shows plan features and costs. Early Access page is a simple sign-up form for pre-launch access with early-bird pricing.
				</p>
				<p class="ds-type-meta">
					<strong>When to use:</strong> For contractors who want to join before public launch. Should be linked from homepage and pricing pages.
				</p>
				<p><strong>Section Structure (4 sections only):</strong></p>
				<ol>
					<li><strong>Simple Hero</strong> - H1 title + supporting paragraph only</li>
					<li><strong>Form</strong> - Sign-up form (name, email, company, phone)</li>
					<li><strong>Benefits</strong> - 3 benefit cards (early-bird pricing, feedback loop, priority onboarding)</li>
					<li><strong>Global CTA</strong> - Standard `.rankings-cta` component linking to demo</li>
				</ol>
				<p class="ds-type-meta">
					<strong>Components used:</strong> `.rankings-section`, `.rankings-header`, `.ranking-factors-grid`, `.rankings-cta`, standard form components.
				</p>
			</div>

			<div class="ds-subsection">
				<h3>Directory Main Page</h3>
				<p>Public directory of contractors ranked by verified work activity.</p>
				<p class="ds-type-meta">
					<strong>Purpose:</strong> Allow homeowners to discover and evaluate contractors based on real, verified job activity. This is a utility page, not a marketing page.
				</p>
				<p class="ds-type-meta">
					<strong>How it differs from marketing pages:</strong> Directory focuses on search, filtering, and data display. Uses minimal marketing language. Feels like a trusted utility tool.
				</p>
				<p class="ds-type-meta">
					<strong>When to use:</strong> Public-facing contractor directory. Should feel professional, calm, and immediately understandable.
				</p>
				<p><strong>Section Structure:</strong></p>
				<ol>
					<li><strong>Hero</strong> - Simple H1 + supporting text using `.rankings-section` pattern</li>
					<li><strong>Search & Filters</strong> - Search bar, city/service filters, sort options, verified toggle</li>
					<li><strong>Results Grid</strong> - Grid of contractor cards (`.jcp-card .directory-card`)</li>
					<li><strong>How Rankings Work</strong> - Standard `.rankings-section` explaining ranking factors</li>
				</ol>
				<p class="ds-type-meta">
					<strong>Components used:</strong> `.rankings-section`, `.rankings-header`, `.jcp-card`, `.directory-card`, `.ranking-factor-card`, `.rankings-cta`.
				</p>
				<p class="ds-type-meta">
					<strong>Directory Listing Card:</strong> Each contractor card uses `.jcp-card .directory-card` classes. Contains: company name, location, badge (Verified/Trusted Pro/Listed), job count, activity status, rating, and "View activity" link.
				</p>
			</div>

			<div class="ds-subsection">
				<h3>Company Profile Page</h3>
				<p>Individual contractor profile page showing detailed activity, work history, and trust signals.</p>
				<p class="ds-type-meta">
					<strong>Purpose:</strong> Provide detailed information about a single contractor, including verified job activity, recent work photos, location map, and trust indicators.
				</p>
				<p class="ds-type-meta">
					<strong>How it differs from directory:</strong> Directory shows listings in a grid. Profile page shows deep detail for one contractor. Uses standard section wrappers for consistency.
				</p>
				<p class="ds-type-meta">
					<strong>When to use:</strong> When a user clicks on a contractor card from the directory. Should feel like a natural continuation of the directory experience.
				</p>
				<p><strong>Section Structure:</strong></p>
				<ol>
					<li><strong>Profile Hero</strong> - Company name, location, badge, rating, stats (`.rankings-section` with `.jcp-card .hero-card`)</li>
					<li><strong>Activity Map</strong> - Google Maps iframe showing job locations (`.rankings-section` with `.jcp-card .map-card`)</li>
					<li><strong>Recent Work</strong> - Grid of work cards with photos (`.rankings-section` with `.jcp-card .work-card`)</li>
					<li><strong>CTA</strong> - Standard `.rankings-cta` component for requesting contractor</li>
				</ol>
				<p class="ds-type-meta">
					<strong>Components used:</strong> `.rankings-section`, `.rankings-header`, `.jcp-card`, `.hero-card`, `.work-card`, `.rankings-cta`.
				</p>
				<p class="ds-type-meta">
					<strong>Work Cards:</strong> Each recent work item uses `.jcp-card .work-card`. Contains: photo, title, description, and location/time metadata. Cards inherit standard card hover effects.
				</p>
			</div>
		</div>
	</section>

	<!-- ============================================================
	     FOOTER
	     ============================================================ -->
	<section class="ds-section">
		<div class="jcp-container">
			<h2>Footer</h2>
			
			<div class="ds-subsection">
				<h3>Standard Footer Layout</h3>
				<p>Global footer used on all pages. Not page-specific variations.</p>
				<div class="ds-demo" style="background: #1f2937; color: white; padding: var(--jcp-space-5xl) 0; border-radius: 8px; margin-top: var(--jcp-space-lg);">
					<div class="jcp-container">
						<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: var(--jcp-space-lg); margin-bottom: var(--jcp-space-5xl);">
							<div>
								<h4 style="color: white; margin-bottom: var(--jcp-space-md);">Product</h4>
								<ul style="list-style: none; padding: 0; margin: 0;">
									<li><a href="#" style="color: #d1d5db; text-decoration: none; font-size: 14px;">Features</a></li>
									<li><a href="#" style="color: #d1d5db; text-decoration: none; font-size: 14px;">Pricing</a></li>
									<li><a href="#" style="color: #d1d5db; text-decoration: none; font-size: 14px;">Security</a></li>
								</ul>
							</div>
							<div>
								<h4 style="color: white; margin-bottom: var(--jcp-space-md);">Company</h4>
								<ul style="list-style: none; padding: 0; margin: 0;">
									<li><a href="#" style="color: #d1d5db; text-decoration: none; font-size: 14px;">About</a></li>
									<li><a href="#" style="color: #d1d5db; text-decoration: none; font-size: 14px;">Blog</a></li>
									<li><a href="#" style="color: #d1d5db; text-decoration: none; font-size: 14px;">Careers</a></li>
								</ul>
							</div>
							<div>
								<h4 style="color: white; margin-bottom: var(--jcp-space-md);">Legal</h4>
								<ul style="list-style: none; padding: 0; margin: 0;">
									<li><a href="#" style="color: #d1d5db; text-decoration: none; font-size: 14px;">Privacy</a></li>
									<li><a href="#" style="color: #d1d5gb; text-decoration: none; font-size: 14px;">Terms</a></li>
									<li><a href="#" style="color: #d1d5db; text-decoration: none; font-size: 14px;">Contact</a></li>
								</ul>
							</div>
						</div>
						<div style="border-top: 1px solid #374151; padding-top: var(--jcp-space-lg); text-align: center; color: #9ca3af; font-size: 14px;">
							<p>&copy; 2026 JobCapturePro. All rights reserved.</p>
						</div>
					</div>
				</div>
			</div>

			<div class="ds-subsection">
				<h3>Footer Specifications</h3>
				<ul>
					<li><strong>Background:</strong> var(--jcp-color-secondary) (#1f2937)</li>
					<li><strong>Text color:</strong> Light gray (#d1d5db) for links, lighter gray for muted text</li>
					<li><strong>Padding:</strong> var(--jcp-space-5xl) 0 (64px top/bottom)</li>
					<li><strong>Grid:</strong> Auto-fit columns at 200px minimum</li>
					<li><strong>Column gap:</strong> var(--jcp-space-lg) (24px)</li>
					<li><strong>Link hover:</strong> White text with smooth transition</li>
				</ul>
			</div>
		</div>
	</section>

	<!-- ============================================================
	     UTILITIES & HELPERS
	     ============================================================ -->
	<section class="ds-section">
		<div class="jcp-container">
			<h2>Utility Classes & Helpers</h2>
			
			<div class="ds-subsection">
				<h3>Text Utilities</h3>
				<ul>
					<li><code>.jcp-text-primary</code> - Default text color</li>
					<li><code>.jcp-text-secondary</code> - Secondary text color</li>
					<li><code>.jcp-text-muted</code> - Muted/tertiary text</li>
					<li><code>.jcp-text-light</code> - Light text for dark backgrounds</li>
					<li><code>.jcp-text-sm</code> - Small text (14px)</li>
					<li><code>.jcp-text-center</code> - Center-aligned text</li>
				</ul>
			</div>

			<div class="ds-subsection">
				<h3>Container & Layout</h3>
				<ul>
					<li><code>.jcp-container</code> - Standard page container (1240px max, 94% responsive)</li>
					<li><code>.jcp-grid</code> - Auto-fit grid layout</li>
					<li><code>.jcp-section</code> - Major section wrapper</li>
				</ul>
			</div>

			<div class="ds-subsection">
				<h3>Margin & Padding</h3>
				<p>Spacing utilities use the 8px scale:</p>
				<ul>
					<li><code>.jcp-m-xs</code> through <code>.jcp-m-6xl</code> - Margin</li>
					<li><code>.jcp-p-xs</code> through <code>.jcp-p-6xl</code> - Padding</li>
					<li><code>.jcp-gap-xs</code> through <code>.jcp-gap-6xl</code> - Grid gap</li>
				</ul>
			</div>
		</div>
	</section>

	<!-- ============================================================
	     STANDARD PAGE TEMPLATE & BLOG SYSTEM
	     ============================================================ -->
	<section class="ds-section">
		<div class="jcp-container">
			<h2>Standard Page Template & Blog System</h2>
			
			<div class="ds-subsection">
				<h3>Standard Page Template (<code>page.php</code>)</h3>
				<p>Default template for WordPress pages. Content added via WP Admin editor renders cleanly with proper spacing and typography.</p>
				
				<h4>Structure</h4>
				<ul>
					<li><strong>Wrapper:</strong> <code>&lt;main class="jcp-marketing"&gt;</code></li>
					<li><strong>Section:</strong> <code>&lt;section class="jcp-section rankings-section"&gt;</code></li>
					<li><strong>Container:</strong> <code>&lt;div class="jcp-container"&gt;</code></li>
					<li><strong>Content:</strong> <code>&lt;div class="jcp-page-content"&gt;</code> (max-width: 800px, centered)</li>
				</ul>

				<h4>Content Styling</h4>
				<p>All content from WordPress editor is automatically styled:</p>
				<ul>
					<li><strong>Headings:</strong> H1-H6 use design system typography scale</li>
					<li><strong>Paragraphs:</strong> <code>font-size: var(--jcp-font-size-lg)</code>, <code>line-height: var(--jcp-line-height-relaxed)</code></li>
					<li><strong>Links:</strong> Primary color, underlined</li>
					<li><strong>Images:</strong> Max-width 100%, border-radius applied</li>
					<li><strong>Lists:</strong> Proper spacing and indentation</li>
					<li><strong>Blockquotes:</strong> Left border accent, italic text</li>
					<li><strong>Code:</strong> Monospace font, background highlight</li>
				</ul>

				<h4>Backend Editing</h4>
				<p>Non-technical users can create clean pages from WP Admin without custom styling. All styles are automatic.</p>
			</div>

			<div class="ds-subsection">
				<h3>Blog System Templates</h3>
				<p>Complete blog system using existing design system components.</p>

				<h4>Available Templates</h4>
				<ul>
					<li><code>home.php</code> - Blog archive (when blog is homepage)</li>
					<li><code>archive.php</code> - Generic archive template</li>
					<li><code>category.php</code> - Category archive</li>
					<li><code>tag.php</code> - Tag archive</li>
					<li><code>single.php</code> - Single post template</li>
					<li><code>search.php</code> - Search results</li>
					<li><code>comments.php</code> - Comments template</li>
				</ul>

				<h4>Blog Archive Layout</h4>
				<ul>
					<li><strong>Header:</strong> Uses <code>.rankings-header</code> component</li>
					<li><strong>Grid:</strong> <code>.jcp-blog-grid</code> - Responsive grid (auto-fill, minmax 320px)</li>
					<li><strong>Cards:</strong> Uses existing <code>.jcp-card</code> component</li>
					<li><strong>Pagination:</strong> Uses existing button styles</li>
				</ul>

				<h4>Post Card Component</h4>
				<p>Template part: <code>templates/content/content-post-card.php</code></p>
				<ul>
					<li><strong>Image:</strong> Featured image (if available)</li>
					<li><strong>Category:</strong> First category displayed</li>
					<li><strong>Title:</strong> Linked to post</li>
					<li><strong>Excerpt:</strong> Post excerpt</li>
					<li><strong>Meta:</strong> Date and "Read more" link</li>
				</ul>

				<h4>Single Post Layout</h4>
				<ul>
					<li><strong>Header:</strong> Title, date, categories</li>
					<li><strong>Content:</strong> Full post content (max-width: 800px, centered)</li>
					<li><strong>Footer:</strong> Tags (if available)</li>
					<li><strong>Navigation:</strong> Previous/Next post links</li>
					<li><strong>Comments:</strong> Comments template (if enabled)</li>
				</ul>

				<h4>Pagination</h4>
				<p>Template part: <code>templates/partials/pagination.php</code></p>
				<ul>
					<li>Uses WordPress <code>paginate_links()</code></li>
					<li>Styled with existing button components</li>
					<li>Accessible markup with screen reader text</li>
				</ul>

				<h4>Empty States</h4>
				<p>Template part: <code>templates/content/content-none.php</code></p>
				<ul>
					<li>Centered layout</li>
					<li>Clear messaging</li>
					<li>Search form (when appropriate)</li>
				</ul>
			</div>

			<div class="ds-subsection">
				<h3>Design System Alignment</h3>
				<p>All blog templates use:</p>
				<ul>
					<li>Existing typography scale (<code>--jcp-font-size-*</code>)</li>
					<li>Existing spacing scale (<code>--jcp-space-*</code>)</li>
					<li>Existing container width (<code>.jcp-container</code>)</li>
					<li>Existing card components (<code>.jcp-card</code>)</li>
					<li>Existing button styles (<code>.btn</code>)</li>
					<li>Existing section wrappers (<code>.rankings-section</code>)</li>
				</ul>
				<p><strong>No blog-specific card styles or new typography introduced.</strong></p>
			</div>
		</div>
	</section>

	<!-- ============================================================
	     RULES & CONSTRAINTS
	     ============================================================ -->
	<section class="ds-section" style="background: #f3f4f6; border-radius: 8px;">
		<div class="jcp-container">
			<h2>Design System Rules</h2>
			
			<div class="ds-subsection">
				<h3>Golden Rules (Non-Negotiable)</h3>
				<ul style="list-style-type: none; padding: 0;">
					<li style="padding: var(--jcp-space-sm) 0;"><strong style="color: var(--jcp-color-error);">❌ NO</strong> new layouts outside this design system</li>
					<li style="padding: var(--jcp-space-sm) 0;"><strong style="color: var(--jcp-color-error);">❌ NO</strong> page-specific CSS unless approved</li>
					<li style="padding: var(--jcp-space-sm) 0;"><strong style="color: var(--jcp-color-error);">❌ NO</strong> quick fixes on individual pages</li>
					<li style="padding: var(--jcp-space-sm) 0;"><strong style="color: var(--jcp-color-error);">❌ NO</strong> inline styles in templates</li>
					<li style="padding: var(--jcp-space-sm) 0;"><strong style="color: var(--jcp-color-error);">❌ NO</strong> one-off component variations</li>
				</ul>
			</div>

			<div class="ds-subsection">
				<h3>Component Creation Rules</h3>
				<ol>
					<li><strong>Define here first:</strong> Every new component or variation must be shown on this page before being implemented</li>
					<li><strong>Reusable always:</strong> If a component isn't reusable across multiple pages, it's not a component</li>
					<li><strong>States documented:</strong> Every component must show all states (default, hover, active, disabled, loading)</li>
					<li><strong>Variants clear:</strong> If a component has variants, all variants must be documented here</li>
					<li><strong>Spacing from scale:</strong> All margins/padding must use the 8px spacing scale</li>
				</ol>
			</div>

			<div class="ds-subsection">
				<h3>Spacing Rules</h3>
				<ol>
					<li><strong>Use the scale:</strong> Only use spacing values from <code>--jcp-space-xs</code> through <code>--jcp-space-6xl</code></li>
					<li><strong>Sections padding:</strong> All major sections use <code>var(--jcp-space-5xl) 0</code> (64px top/bottom)</li>
					<li><strong>Grid gaps:</strong> Use <code>var(--jcp-space-lg)</code> (24px) for gaps between grid items</li>
					<li><strong>Vertical rhythm:</strong> Related elements should use smaller spacing; separated elements use larger spacing</li>
				</ol>
			</div>

			<div class="ds-subsection">
				<h3>Typography Rules</h3>
				<ol>
					<li><strong>Hierarchy matters:</strong> Use H1-H6 for semantic structure AND visual hierarchy</li>
					<li><strong>Size scale only:</strong> Only use defined font sizes from <code>--jcp-font-size-xs</code> through <code>--jcp-font-size-6xl</code></li>
					<li><strong>Weight consistency:</strong> Follow the weight scale (400 body, 600 headings, 700 section titles)</li>
					<li><strong>Line height locked:</strong> Line height defined per size; don't override</li>
				</ol>
			</div>

			<div class="ds-subsection">
				<h3>Color Rules</h3>
				<ol>
					<li><strong>Use CSS variables:</strong> Never hardcode colors. Always use <code>var(--jcp-color-*)</code></li>
					<li><strong>Brand consistency:</strong> Primary color (#ff5036) should appear consistently across CTAs</li>
					<li><strong>Contrast:</strong> Text must have sufficient contrast (WCAG AA minimum)</li>
					<li><strong>Dark background text:</strong> Must always be white (white text on orange button rule)</li>
				</ol>
			</div>
		</div>
	</section>

	<!-- ============================================================
	     VERSION & MAINTENANCE
	     ============================================================ -->
	<section class="ds-section">
		<div class="jcp-container">
			<h2>Version & Maintenance</h2>
			
			<div class="ds-subsection">
				<h3>Design System Version</h3>
				<p><strong>Current version:</strong> 1.0.0 (PHASE 2 - Initial Extraction)</p>
				<p><strong>Last updated:</strong> January 26, 2026</p>
				<p><strong>Status:</strong> Active development (Phase 2 → Phase 3 → Phase 4)</p>
			</div>

			<div class="ds-subsection">
				<h3>How to Update</h3>
				<ol>
					<li><strong>Before changing anything:</strong> Update this page first</li>
					<li><strong>Get approval:</strong> All changes must follow the golden rules above</li>
					<li><strong>Update tokens:</strong> If spacing/colors change, update <code>design-system.css</code></li>
					<li><strong>Update components:</strong> Update component templates and CSS</li>
					<li><strong>Verify consistency:</strong> Check that all pages using component render correctly</li>
					<li><strong>Commit atomically:</strong> Component + design system page updates in same commit</li>
				</ol>
			</div>

			<div class="ds-subsection">
				<h3>Questions or Issues?</h3>
				<p>This design system is the source of truth. If you find something that doesn't match, update this page, then update the code to match.</p>
			</div>
		</div>
	</section>

</div><!-- #jcp-design-system-page -->

<?php get_footer(); ?>
