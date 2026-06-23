<x-layout.public-layout title="Privacy Policy" metaDescription="Privacy Policy for our World Cup Discord Bot." :canonical="url('/privacy-policy')" :metaImage="null" bodyClass="legal-page">
  <section class="page-section legal-section">
    <div class="container">
      <div class="legal-card card-soft">
        <span class="legal-badge">
          Privacy
        </span>

        <h1>Privacy Policy</h1>

        <p class="legal-updated">
          Last updated: {{ now()->format('d F Y') }}
        </p>

        <p>
          This Privacy Policy explains how our World Cup Discord Bot handles data.
        </p>

        <h2>1. Information We Collect</h2>
        <p>
          The bot may temporarily process basic Discord data required to respond to commands, such as:
        </p>

        <ul>
          <li>Server ID</li>
          <li>Channel ID</li>
          <li>User command interaction</li>
          <li>Command name</li>
          <li>Basic Discord user ID related to the command</li>
        </ul>

        <p>
          We do not collect passwords, private messages, payment information, or sensitive personal data.
        </p>

        <h2>2. How We Use Data</h2>
        <p>We use this data only to:</p>

        <ul>
          <li>Respond to slash commands</li>
          <li>Show match fixtures</li>
          <li>Show live match information</li>
          <li>Improve bot reliability and debugging</li>
        </ul>

        <h2>3. Data Storage</h2>
        <p>
          The bot does not permanently store normal user messages. Some technical logs may be stored
          temporarily for debugging and security purposes.
        </p>

        <h2>4. Third-Party Services</h2>
        <p>
          The bot runs through Discord and may connect to our own Laravel API to retrieve match data.
          Discord's own Privacy Policy also applies when using Discord.
        </p>

        <h2>5. Data Sharing</h2>
        <p>
          We do not sell, rent, or share user data with advertisers or unrelated third parties.
        </p>

        <h2>6. Security</h2>
        <p>
          We use API authentication and server-side protections to prevent unauthorized access to our bot API.
        </p>

        <h2>7. Contact</h2>
        <p>
          For privacy questions or data-related requests, contact us at
          <a href="mailto:support@nam-nai.com">support@nam-nai.com</a>.
        </p>
      </div>
    </div>
  </section>
</x-layout.public-layout>
s
