roberto
=======

Auto-playing web bot
January 2011

I. Introduction.

Cartowars.com is a web free-to-play multiplayer online game.
The concept is simple: the player creates and organizes his deck with up to 30 cards (monsters and equipments).
Then he battles others players in order to go up the ladder and get better rewards (real money).
The battle system is very simple: you card battles the opponent's card. If you defeat his first card, you now battle his second card and so on. You have no control during the battle.

So I made Roberto the robot to play automatically for me and eventually get me rewards :)

II. What's your job Roberto?

Roberto manages several accounts and is launched once an hour.
He gets the daily free card and transfers the in-game emails to the user's real email address. Therefore, the player can reply and doesn't look suspicious.
Then, he battles other players! Each time he launches a battle, he stores the opponent's deck into a MySQL database.
Thanks to his cross-accounts shared memory and his integrated battle simulator, Roberto doesn't battle against a player if he knows he's going to loose.
If he met a new opponent, he battles him in order to discover his deck.

III. Then what?

Here are my ideas to improve Roberto. But as Cartowars makes not enough profit even if you're the best, I won't develop those features:
- An AI that re-organize the player's deck in order to be able to win a battle you previously lost.

And that's pretty much all!
