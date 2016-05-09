# Geographical data
# =================
# Sample tables and queries for storing and retrieving
# simple geographical data.

# Sample table structure
CREATE TABLE `user_positions` (
  `user_id` int(11) NOT NULL,
  `lat` float NOT NULL,
  `lng` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Unique index (ensures each user has only one entry)
ALTER TABLE `user_positions`
  ADD UNIQUE KEY `user_id` (`user_id`);

# Optional index (improves geographical query performance)
ALTER TABLE `user_positions`
  ADD KEY `lat` (`lat`),
  ADD KEY `lng` (`lng`);



# Boundary-box selection
# (Assuming $lat and $lng are the center of the box.)
SELECT `user_id` FROM `user_positions`
  WHERE `lat` BETWEEN $lat-0.1 AND $lat+0.1
  AND `lng` BETWEEN $lng-0.1 AND $lng+0.1;

# Sort by distance and pick first result (i.e., "pick closest")
# (Uses the euclidean distance formula.)
SELECT *, SQRT(POW($lat - lat, 2) + POW($lng - lng, 2)) AS distance
  FROM `user_positions`
  ORDER BY distance ASC
  LIMIT 1;
