# Charitable Progress Circle

This is a bare-bones plugin that replaces the default horizontal progress bar with a progress circle (sort of like the one on Reach).

## Usage

Just install it!

If you would like to display a progress circle for a particular campaign somewhere else, use the following code:

```
// First get the campaign.
$campaign = charitable_get_campaign( 123 );

// Then display the progress circle.
charitable_template_campaign_percentage_raised( $campaign );
```

Replace `123` with the ID of your campaign.
