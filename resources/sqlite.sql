-- #!sqlite
-- #{ invite
-- #    { init
CREATE TABLE IF NOT EXISTS `invites` (
    username TEXT PRIMARY KEY,
    invites INTEGER DEFAULT 0,
    createAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    user_info TEXT DEFAULT NULL
);
-- #    }
-- #    { add
-- #	:username string
UPDATE invites SET invites = invites + 1 WHERE username = :string;
-- #    }
-- #    { insert
-- #	    :username string
-- #	    :invites int
-- #        :createAt datetime
-- #        :user_info string
INSERT OR IGNORE INTO invites (username, invites, createAt, user_info) VALUES (:username, :invites, :createAt, :user_info);
-- #    }
-- #    { get
-- #	    :username string
SELECT username, invites, createAt, user_info FROM invites WHERE username = :username;
-- #    }
-- #    { top
-- #	    :limit int
SELECT username, invites FROM invites ORDER BY LIMIT :limit;
-- #    }
-- #    { reset
DELETE FROM invites;
-- #    }
-- #}