# Unique voting
# =============
# A system for storing unique votes per day and per user,
# that is, the same user can vote once each day.

# Table structure
CREATE TABLE `votes` (
  `user_id` int(11) NOT NULL,
  `vote` int(1) NOT NULL,
  `day` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Unique index on table (ensures a user votes only once per day)
ALTER TABLE `votes`
  ADD UNIQUE KEY `user_key` (`user_id`,`day`);



# Query to store a user's vote
# (Assuming that $from_id is the user's Telegram ID
# and $vote the user's vote.)
REPLACE INTO `votes` VALUES($from_id, $vote, NOW());

# Query a user's vote of today
SELECT * FROM `votes` WHERE user_id = $from_id AND `day` = CURDATE();

# Count total number of votes
SELECT COUNT(*) FROM `votes`;

# Count total number of votes of today
SELECT COUNT(*) FROM `votes` WHERE `day` = CURDATE();

# Count total number of voting users
SELECT COUNT(DISTINCT `user_id`) AS `voting users` FROM `votes`;

# Users and votes cast in total
SELECT `user_id`, COUNT(*) as `vote count` FROM `votes` GROUP BY `user_id`;

/* Example result:

user_id	  | vote count
======================
178430472 | 2
178430498 | 1
178430499 | 3
*/
