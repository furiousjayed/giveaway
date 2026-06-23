<x-layout.public-layout title="Terms of Service" metaDescription="Terms of Service for our World Cup Discord Bot." :canonical="url('/terms-of-service')" :metaImage="null" bodyClass="legal-page">
  <section class="page-section legal-section">
    <div class="container">
      <div class="legal-card card-soft">
        <span class="legal-badge">
          Legal
        </span>

        <h1>Terms of Service</h1>

        <p class="legal-updated">
          Last updated: {{ now()->format('d F Y') }}
        </p>

        <p>
          Welcome to our World Cup Discord Bot. By adding or using this bot in your Discord server,
          you agree to these Terms of Service.
        </p>

        <h2>1. Service Description</h2>
        <p>
          This bot provides football match fixture information, live match status, and related
          World Cup updates inside Discord servers.
        </p>

        <h2>2. Usage</h2>
        <p>
          You may use this bot only for lawful and normal Discord server purposes. You must not abuse,
          spam, reverse engineer, attack, overload, or attempt to bypass the bot or API security.
        </p>

        <h2>3. Data Accuracy</h2>
        <p>
          Match data is provided for informational purposes only. We try to keep fixture and live match
          information accurate, but we do not guarantee that all data will always be complete, correct,
          or real-time.
        </p>

        <h2>4. Availability</h2>
        <p>
          The bot may be unavailable during maintenance, server downtime, API issues, Discord outages,
          or technical errors.
        </p>

        <h2>5. Limitation of Liability</h2>
        <p>
          We are not responsible for any loss, issue, or damage caused by using or being unable to use the bot.
        </p>

        <h2>6. Changes</h2>
        <p>
          We may update these Terms of Service at any time. Continued use of the bot means you accept
          the updated terms.
        </p>

        <h2>7. Contact</h2>
        <p>
          For support or questions, contact us at
          <a href="mailto:support@nam-nai.com">support@nam-nai.com</a>.
        </p>
      </div>
    </div>
  </section>
</x-layout.public-layout>
