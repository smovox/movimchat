<?php

$base = __DIR__.'/';

define('XMPP_LIB_NAME', 'moxl');

require_once($base.'MoxlLogger.php');
require_once($base.'MoxlAuth.php');
require_once($base.'MoxlAPI.php');
require_once($base.'MoxlRequest.php');
require_once($base.'MoxlUtils.php');

require_once($base.'stanza/base/Message.php');
require_once($base.'stanza/base/Muc.php');
require_once($base.'stanza/base/Presence.php');
require_once($base.'stanza/base/Roster.php');
require_once($base.'stanza/base/Vcard.php');

require_once($base.'stanza/bookmark/Bookmark.php');

require_once($base.'stanza/microblog/Microblog.php');

require_once($base.'stanza/group/Group.php');

require_once($base.'stanza/pubsub/Pubsub.php');
require_once($base.'stanza/pubsub/PubsubAtom.php');

require_once($base.'stanza/pubsubsubscription/PubsubSubscription.php');

require_once($base.'stanza/notification/Notification.php');

require_once($base.'stanza/storage/Storage.php');

require_once($base.'stanza/disco/Disco.php');

require_once($base.'stanza/location/Location.php');

require_once($base.'stanza/version/Version.php');

// XEC loader

require_once($base.'xec/XECAction.php');
require_once($base.'xec/XECPayload.php');

// To handle error generated par Moxl requests
require_once($base.'xec/payload/MoxlRequestError.php');

require_once($base.'xec/action/pubsub/PubsubErrors.php');

require_once($base.'xec/action/caps/DiscoRequest.php');

require_once($base.'xec/action/bookmark/BookmarkGet.php');
require_once($base.'xec/action/bookmark/BookmarkSet.php');

require_once($base.'xec/action/roster/RosterGetList.php');
require_once($base.'xec/action/roster/RosterAddItem.php');
require_once($base.'xec/action/roster/RosterUpdateItem.php');
require_once($base.'xec/action/roster/RosterRemoveItem.php');

require_once($base.'xec/action/presence/PresenceAway.php');
require_once($base.'xec/action/presence/PresenceChat.php');
require_once($base.'xec/action/presence/PresenceDND.php');
require_once($base.'xec/action/presence/PresenceSubscribe.php');
require_once($base.'xec/action/presence/PresenceSubscribed.php');
require_once($base.'xec/action/presence/PresenceUnavaiable.php');
require_once($base.'xec/action/presence/PresenceUnsubscribe.php');
require_once($base.'xec/action/presence/PresenceUnsubscribed.php');
require_once($base.'xec/action/presence/PresenceXA.php');
require_once($base.'xec/action/presence/PresenceMuc.php');

require_once($base.'xec/action/vcard/VcardGet.php');
require_once($base.'xec/action/vcard/VcardSet.php');

require_once($base.'xec/action/message/MessagePublish.php');
require_once($base.'xec/action/message/MessageComposing.php');
require_once($base.'xec/action/message/MessagePaused.php');

require_once($base.'xec/action/microblog/MicroblogCreateNode.php');
require_once($base.'xec/action/microblog/MicroblogCommentsGet.php');
require_once($base.'xec/action/microblog/MicroblogCommentPublish.php');
require_once($base.'xec/action/microblog/MicroblogCommentCreateNode.php');

require_once($base.'xec/action/notification/NotificationGet.php');
require_once($base.'xec/action/notification/NotificationItemDelete.php');

require_once($base.'xec/action/storage/StorageGet.php');
require_once($base.'xec/action/storage/StorageSet.php');

require_once($base.'xec/action/location/LocationPublish.php');


require_once($base.'xec/action/group/GroupCreate.php');
require_once($base.'xec/action/group/GroupDelete.php');

require_once($base.'xec/action/pubsubsubscription/PubsubSubscriptionListAdd.php');
require_once($base.'xec/action/pubsubsubscription/PubsubSubscriptionListGet.php');
require_once($base.'xec/action/pubsubsubscription/PubsubSubscriptionListGetFriends.php');
require_once($base.'xec/action/pubsubsubscription/PubsubSubscriptionListRemove.php');

require_once($base.'xec/action/version/VersionSend.php');

// Pubsub Actions

require_once($base.'xec/action/pubsub/PubsubPostPublish.php');
require_once($base.'xec/action/pubsub/PubsubPostDelete.php');
require_once($base.'xec/action/pubsub/PubsubGetItems.php');
require_once($base.'xec/action/pubsub/PubsubDiscoItems.php');
require_once($base.'xec/action/pubsub/PubsubGetConfig.php');
require_once($base.'xec/action/pubsub/PubsubSetConfig.php');
require_once($base.'xec/action/pubsub/PubsubGetAffiliations.php');
require_once($base.'xec/action/pubsub/PubsubSetAffiliations.php');
require_once($base.'xec/action/pubsub/PubsubGetSubscriptions.php');
require_once($base.'xec/action/pubsub/PubsubSetSubscriptions.php');
require_once($base.'xec/action/pubsub/PubsubSubscribe.php');
require_once($base.'xec/action/pubsub/PubsubUnsubscribe.php');

require_once($base.'xec/XECHandler.array.php');
require_once($base.'xec/XECHandler.php');
