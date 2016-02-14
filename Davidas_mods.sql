-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 07, 2011 at 05:06 PM
-- Server version: 5.1.41
-- PHP Version: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `mydb`
--

-- --------------------------------------------------------

--
-- Table structure for table `competency`
--

DROP TABLE IF EXISTS `competency`;
CREATE TABLE IF NOT EXISTS `competency` (
  `CompetencyID` int(11) NOT NULL AUTO_INCREMENT,
  `CompName` varchar(60) NOT NULL,
  `DisplayOrder` int(11) NOT NULL,
  `CompShorthand` varchar(20) NOT NULL,
  PRIMARY KEY (`CompetencyID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Information for each competency measured on each rubric.' AUTO_INCREMENT=6 ;

--
-- Dumping data for table `competency`
--

INSERT INTO `competency` (`CompetencyID`, `CompName`, `DisplayOrder`, `CompShorthand`) VALUES
(1, 'Evidence of Research and Quality of Sources', 1, 'Res_qual'),
(2, 'Mechanics of Citation', 2, 'Citation'),
(3, 'Ideas & Integration of Sources into Content', 3, 'Ideas'),
(4, 'Writing', 4, 'Writing'),
(5, 'Holistic & Sophistication', 5, 'hol_soph');

-- --------------------------------------------------------

--
-- Table structure for table `competencytovalues`
--

DROP TABLE IF EXISTS `competencytovalues`;
CREATE TABLE IF NOT EXISTS `competencytovalues` (
  `CompValueID` int(11) NOT NULL,
  `CompetencyID` int(11) NOT NULL,
  `ExtendedID` int(11) DEFAULT NULL,
  KEY `fk_CompetencyToValues_CompetencyValue1` (`CompValueID`),
  KEY `fk_CompetencyToValues_Competency1` (`CompetencyID`),
  KEY `fk_CompetencyToValues_CompExtendedDescription1` (`ExtendedID`),
  KEY `PK_CompValue_Competency` (`CompValueID`,`CompetencyID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `competencytovalues`
--

INSERT INTO `competencytovalues` (`CompValueID`, `CompetencyID`, `ExtendedID`) VALUES
(1, 1, NULL),
(2, 1, NULL),
(3, 1, NULL),
(4, 1, NULL),
(5, 1, NULL),
(6, 1, NULL),
(7, 1, NULL),
(8, 1, NULL),
(9, 1, NULL),
(10, 1, NULL),
(1, 2, NULL),
(2, 2, NULL),
(3, 2, NULL),
(4, 2, NULL),
(5, 2, NULL),
(6, 2, NULL),
(7, 2, NULL),
(8, 2, NULL),
(9, 2, NULL),
(10, 2, NULL),
(1, 3, NULL),
(2, 3, NULL),
(3, 3, NULL),
(4, 3, NULL),
(5, 3, NULL),
(6, 3, NULL),
(7, 3, NULL),
(8, 3, NULL),
(9, 3, NULL),
(10, 3, NULL),
(1, 4, NULL),
(2, 4, NULL),
(3, 4, NULL),
(4, 4, NULL),
(5, 4, NULL),
(6, 4, NULL),
(7, 4, NULL),
(8, 4, NULL),
(9, 4, NULL),
(10, 4, NULL),
(1, 5, NULL),
(2, 5, NULL),
(3, 5, NULL),
(4, 5, NULL),
(5, 5, NULL),
(6, 5, NULL),
(7, 5, NULL),
(8, 5, NULL),
(9, 5, NULL),
(10, 5, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `competencyvalue`
--

DROP TABLE IF EXISTS `competencyvalue`;
CREATE TABLE IF NOT EXISTS `competencyvalue` (
  `CompValueID` int(11) NOT NULL AUTO_INCREMENT,
  `CompTextName` varchar(50) NOT NULL,
  `CompValue` int(11) NOT NULL,
  PRIMARY KEY (`CompValueID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Stores the possible scores for the competencies.' AUTO_INCREMENT=11 ;

--
-- Dumping data for table `competencyvalue`
--

INSERT INTO `competencyvalue` (`CompValueID`, `CompTextName`, `CompValue`) VALUES
(1, 'Accomplished +', 10),
(2, 'Accomplished', 9),
(3, 'Accomplished -', 8),
(4, 'Developing +', 7),
(5, 'Developing', 6),
(6, 'Developing -', 5),
(7, 'Poor +', 4),
(8, 'Poor', 3),
(9, 'Poor -', 2),
(10, 'Very Poor', 1);

-- --------------------------------------------------------

--
-- Table structure for table `compextendeddescription`
--

DROP TABLE IF EXISTS `compextendeddescription`;
CREATE TABLE IF NOT EXISTS `compextendeddescription` (
  `ExtendedID` int(11) NOT NULL AUTO_INCREMENT,
  `ExtendedText` varchar(1000) NOT NULL,
  `CompValueID` int(11) NOT NULL,
  PRIMARY KEY (`ExtendedID`),
  KEY `fk_CompExtendedDescription_CompetencyValue1` (`CompValueID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Stores extended descriptions for competency values' AUTO_INCREMENT=21 ;

--
-- Dumping data for table `compextendeddescription`
--

INSERT INTO `compextendeddescription` (`ExtendedID`, `ExtendedText`, `CompValueID`) VALUES
(1, 'Identifies and selects resources that are relevant and that meet all or nearly all of the important criteria - sensitive to issues of validity, timeliness, and sufficiency. Able to identify valid sources that have been reliably reviewed by those recognized as knowledgeable about the topic at hand, to select sources that offer time-appropriate views on that topic, and to ensure that the sources used are adequate to support the demands of the topic.', 1),
(2, 'Used at least two sources beyond those given.  Enough info about the source to indicate it was sought and selected from among several.  Quality may be uneven.  May have some issues of reliability, sufficiency, relevance, etc.  May show some confusion on types of sources.', 1),
(3, 'Alludes to data from at least one source beyond the syllabus, but may miss the key idea completely.  Quality of sources may be highly questionable or seriously flawed', 1),
(4, 'No evidence that research was conducted.  If any sources are used beyond those given there is a superficial or incorrect understanding.  May be no evidence that the source was read.', 1),
(5, 'All cited sources are correct, consistent and in a recognized format (APA/MLA).  Citation elements are present, but there may be a minor formatting error.', 2),
(6, 'The citation is missing a minor data element such as author''s name or page number, but the source is still findable without undue burden.  Appropriate links between in-text and full references.', 2),
(7, 'Citation is missing critical information, such as the journal name or article title.  Mostly lacking correct in-text/endnote correspondence.  Reference list may not be alphabetized.', 2),
(8, 'Citations are missing, or minimal with only url present, or only the title and author.', 2),
(9, 'Uses evidence appropriate and effectively, providing sufficient evidence and explanation to convince.', 3),
(10, 'Begins to offer reasons to support its points, perhaps using varied kinds of evidence.  Begins to interpret the evidence and explain connections between evidence and main ideas.  Its examples bear some relevance.', 3),
(11, 'Often uses generalizations to support its points.  May use examples, but they may be obvious or not relevant.  Often depends on unsupported opinion or personal experience, or assumes that evidence speaks for itself and needs no application to the point being discussed.  Often has lapses in logic.', 3),
(12, 'Depends on cliches or overgeneralizations for support, or offers little evidence of any kind.  May be personal narrative rather than essay, or summary rather than analysis.', 3),
(13, 'Almost entirely free of spelling, punctuation, and grammatical errors. <p>Chooses words for their precise meaning and uses an appropriate level of specificity.  Sentence style fits paper''s audience and purpose.  Sentences are varied, yet clearly structured and carefully focused, not long and rambling.<p>Uses a logical structure appropriate to paper''s subject, purpose, audience, thesis, and disciplinary field.  Sophisticated transitional sentences often develop one idea from the previous one or identify their logical relations.  It guides the reader through the chain of reasoning or progression of ideas.', 4),
(14, 'May contain a few errors, which may annoy the reader but not impede understanding.<p>Generally uses words accurately and effectively, but may sometimes be too general.  Sentences generally clear, well structured, and focused, though some may be awkward or ineffective.', 4),
(15, 'Usually contains either many mechanical errors or a few important errors that block the reader''s understanding and ability to see connections between thoughts.<p>Uses relatively vague and general words, may use some inappropriate language.  Sentence structure generally correct, but sentences may be wordy, unfocused, repetitive, or confusing.<p>May list ideas or arrange them randomly rather than using any evident logical structure. May use transitions, but they are likely to be sequential (first, second, third) rather than logic-based. While each paragraph may relate to central idea, logic is not always clear. Paragraphs have topic sentences but may be overly general, and arrangement of sentences within paragraphs may lack coherence.', 4),
(16, 'Usually contains either many mechanical errors or a few important errors that block the reader''s understanding and ability to see connections between thoughts.  May contain so many mechanical errors that it is impossible for the reader to follow the thinking from sentence to sentence.<p>May be too vague and abstract, or very personal and specific.  Usually contains several awkward or ungrammatical sentences; sentence structure is simple or monotonous.  Usually contains many awkward sentences, misuses words, employs inappropriate language.', 4),
(17, 'Excels in responding to assignment.  Interesting, demonstrates sophistication of thought.  Central idea/thesis is clearly communicated, worth developing; limited enough to be manageable.  Paper recognizes some complexity of its thesis: may acknowledge its contradictions, qualifications, or limits and follow out their logical implications.  Understands and critically evaluates its sources, appropriate limits and defines terms.', 5),
(18, 'A solid paper, responding appropriately to assignment.  Clearly states a thesis/central idea, but may have minor lapses in development.  Begins to acknowledge the complexity of central idea and the possibility of other points of view.  Shows careful reading of sources, but may not evaluate them critically.  Attempts to define terms, not always successful.', 5),
(19, 'Adequate but weaker and less effective, possibly responding less well to assignment.  Presents central idea in general terms, often depending on platitudes or cliches.  Usually does not acknowledge other views.  Shows basic comprehension of sources, perhaps with lapses in understanding.  If it defines terms, often depends on dictionary definitions.', 5),
(20, 'Does not have a clear central idea or does not respond appropriately to the assignment.  Thesis may be lacking or too vague or obvious to be developed effectively.  Paper may neglect to use any sources or completely misunderstand sources.', 5);

-- --------------------------------------------------------

--
-- Table structure for table `portfoliorating`
--

DROP TABLE IF EXISTS `portfoliorating`;
CREATE TABLE IF NOT EXISTS `portfoliorating` (
  `PortfolioRatingID` int(11) NOT NULL AUTO_INCREMENT,
  `RatingSessionID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `StudentID` int(11) NOT NULL,
  `RatingStartTimer` int(11) DEFAULT NULL,
  `RatingCompletedTime` datetime DEFAULT NULL,
  `IsAdjudicator` bit(1) DEFAULT b'0',
  PRIMARY KEY (`PortfolioRatingID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `portfoliorating`
--

INSERT INTO `portfoliorating` (`PortfolioRatingID`, `RatingSessionID`, `UserID`, `StudentID`, `RatingStartTimer`, `RatingCompletedTime`, `IsAdjudicator`) VALUES
(1, 1, 1, 1, 1307456322, '2011-06-07 10:18:41', b'0'),
(2, 1, 81, 1, 1307456376, '2011-06-07 10:19:23', b'0'),
(3, 1, 82, 2, 1307456433, '2011-06-07 10:20:23', b'0'),
(4, 1, 81, 2, 1307456453, '2011-06-07 10:20:46', b'0'),
(5, 1, 1, 3, 1307456483, '2011-06-07 10:21:14', b'0'),
(7, 1, 81, 3, 1307456536, '2011-06-07 10:22:06', b'0'),
(8, 1, 82, 4, 1307456566, '2011-06-07 10:22:33', b'0'),
(9, 1, 82, 1, 1307457930, '2011-06-07 16:56:48', b'1'),
(10, 1, 1, 2, 1307457972, '2011-06-07 16:56:04', b'1'),
(11, 1, 82, 3, 1307457972, '2011-06-07 16:57:04', b'1');

-- --------------------------------------------------------

--
-- Table structure for table `ratingsession`
--

DROP TABLE IF EXISTS `ratingsession`;
CREATE TABLE IF NOT EXISTS `ratingsession` (
  `RatingSessionID` int(11) NOT NULL AUTO_INCREMENT,
  `RubricID` int(11) NOT NULL,
  `RatingDate` datetime NOT NULL,
  `SessionName` varchar(45) NOT NULL,
  `Threshold` int(11) NOT NULL DEFAULT '1',
  `IsActive` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`RatingSessionID`),
  KEY `fk_RubricID` (`RubricID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='A rating session is associating a rubric to a rating.' AUTO_INCREMENT=3 ;

--
-- Dumping data for table `ratingsession`
--

INSERT INTO `ratingsession` (`RatingSessionID`, `RubricID`, `RatingDate`, `SessionName`, `Threshold`, `IsActive`) VALUES
(1, 1, '2010-05-13 18:51:36', 'Testing', 1, b'1'),
(2, 1, '2010-05-18 18:52:18', 'Information Literacy', 1, b'1');

-- --------------------------------------------------------

--
-- Table structure for table `results`
--

DROP TABLE IF EXISTS `results`;
CREATE TABLE IF NOT EXISTS `results` (
  `id` int(11) NOT NULL,
  `NJITid` int(11) NOT NULL,
  `DocNumber` int(11) NOT NULL,
  `RatingSessionID` int(11) NOT NULL,
  `comp1score1` int(11) NOT NULL,
  `comp1score2` int(11) NOT NULL,
  `comp1score3` int(11) DEFAULT NULL,
  `comp1adj1` int(11) NOT NULL,
  `comp1adj2` int(11) NOT NULL,
  `comp1adjtotal` decimal(4,2) NOT NULL,
  `comp2score1` int(11) NOT NULL,
  `comp2score2` int(11) NOT NULL,
  `comp2score3` int(11) DEFAULT NULL,
  `comp2adj1` int(11) NOT NULL,
  `comp2adj2` int(11) NOT NULL,
  `comp2adjtotal` decimal(4,2) NOT NULL,
  `comp3score1` int(11) NOT NULL,
  `comp3score2` int(11) NOT NULL,
  `comp3score3` int(11) DEFAULT NULL,
  `comp3adj1` int(11) NOT NULL,
  `comp3adj2` int(11) NOT NULL,
  `comp3adjtotal` decimal(4,2) NOT NULL,
  `comp4score1` int(11) NOT NULL,
  `comp4score2` int(11) NOT NULL,
  `comp4score3` int(11) DEFAULT NULL,
  `comp4adj1` int(11) NOT NULL,
  `comp4adj2` int(11) NOT NULL,
  `comp4adjtotal` decimal(4,2) NOT NULL,
  `comp5score1` int(11) NOT NULL,
  `comp5score2` int(11) NOT NULL,
  `comp5score3` int(11) DEFAULT NULL,
  `comp5adj1` int(11) NOT NULL,
  `comp5adj2` int(11) NOT NULL,
  `comp5adjtotal` decimal(4,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `results`
--

INSERT INTO `results` (`id`, `NJITid`, `DocNumber`, `RatingSessionID`, `comp1score1`, `comp1score2`, `comp1score3`, `comp1adj1`, `comp1adj2`, `comp1adjtotal`, `comp2score1`, `comp2score2`, `comp2score3`, `comp2adj1`, `comp2adj2`, `comp2adjtotal`, `comp3score1`, `comp3score2`, `comp3score3`, `comp3adj1`, `comp3adj2`, `comp3adjtotal`, `comp4score1`, `comp4score2`, `comp4score3`, `comp4adj1`, `comp4adj2`, `comp4adjtotal`, `comp5score1`, `comp5score2`, `comp5score3`, `comp5adj1`, `comp5adj2`, `comp5adjtotal`) VALUES
(1, 11122333, 1, 1, 9, 8, NULL, 9, 8, '8.50', 8, 7, 7, 7, 7, '7.00', 9, 8, NULL, 9, 8, '8.50', 9, 8, NULL, 9, 8, '8.50', 8, 7, 4, 7, 4, '5.50'),
(2, 11133444, 2, 1, 4, 5, 7, 5, 7, '6.00', 5, 5, NULL, 5, 5, '5.00', 4, 4, NULL, 4, 4, '4.00', 4, 5, 7, 5, 7, '6.00', 1, 5, 7, 5, 7, '6.00'),
(3, 11144555, 3, 1, 7, 4, 4, 4, 4, '4.00', 6, 8, 7, 8, 7, '7.50', 6, 6, NULL, 6, 6, '6.00', 7, 8, 7, 7, 7, '7.00', 5, 4, 7, 5, 7, '6.00'),
(4, 11155666, 4, 1, 4, 0, NULL, 4, 0, '2.00', 4, 0, NULL, 4, 0, '2.00', 4, 0, NULL, 4, 0, '2.00', 4, 0, NULL, 4, 0, '2.00', 4, 0, NULL, 4, 0, '2.00'),
(5, 11166777, 5, 1, 0, 0, NULL, 0, 0, '0.00', 0, 0, NULL, 0, 0, '0.00', 0, 0, NULL, 0, 0, '0.00', 0, 0, NULL, 0, 0, '0.00', 0, 0, NULL, 0, 0, '0.00'),
(6, 11177888, 8, 1, 0, 0, NULL, 0, 0, '0.00', 0, 0, NULL, 0, 0, '0.00', 0, 0, NULL, 0, 0, '0.00', 0, 0, NULL, 0, 0, '0.00', 0, 0, NULL, 0, 0, '0.00');

-- --------------------------------------------------------

--
-- Table structure for table `rubric`
--

DROP TABLE IF EXISTS `rubric`;
CREATE TABLE IF NOT EXISTS `rubric` (
  `RubricID` int(11) NOT NULL AUTO_INCREMENT,
  `RubricName` varchar(80) NOT NULL,
  PRIMARY KEY (`RubricID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Identifies each rubric to be used for portfolio assessment.' AUTO_INCREMENT=2 ;

--
-- Dumping data for table `rubric`
--

INSERT INTO `rubric` (`RubricID`, `RubricName`) VALUES
(1, 'Information Literacy');

-- --------------------------------------------------------

--
-- Table structure for table `rubriccontent`
--

DROP TABLE IF EXISTS `rubriccontent`;
CREATE TABLE IF NOT EXISTS `rubriccontent` (
  `CompetencyID` int(11) NOT NULL DEFAULT '0',
  `RubricID` int(11) NOT NULL DEFAULT '0',
  `CmpOrder` int(11) NOT NULL,
  PRIMARY KEY (`CompetencyID`,`RubricID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rubriccontent`
--

INSERT INTO `rubriccontent` (`CompetencyID`, `RubricID`, `CmpOrder`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(5, 1, 5);

-- --------------------------------------------------------

--
-- Table structure for table `sessionscoring`
--

DROP TABLE IF EXISTS `sessionscoring`;
CREATE TABLE IF NOT EXISTS `sessionscoring` (
  `SessionScoringID` int(11) NOT NULL AUTO_INCREMENT,
  `CompetencyID` int(11) DEFAULT NULL,
  `PortfolioRatingID` int(11) DEFAULT NULL,
  `Score` int(11) DEFAULT NULL,
  PRIMARY KEY (`SessionScoringID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=45 ;

--
-- Dumping data for table `sessionscoring`
--

INSERT INTO `sessionscoring` (`SessionScoringID`, `CompetencyID`, `PortfolioRatingID`, `Score`) VALUES
(1, 1, 1, 9),
(2, 2, 1, 8),
(3, 3, 1, 9),
(4, 4, 1, 9),
(5, 5, 1, 8),
(6, 1, 2, 8),
(7, 2, 2, 7),
(8, 3, 2, 8),
(9, 4, 2, 8),
(10, 5, 2, 7),
(11, 1, 3, 4),
(12, 2, 3, 5),
(13, 3, 3, 4),
(14, 4, 3, 4),
(15, 5, 3, 1),
(16, 1, 4, 5),
(17, 2, 4, 5),
(18, 3, 4, 4),
(19, 4, 4, 5),
(20, 5, 4, 5),
(21, 1, 5, 7),
(22, 2, 5, 6),
(23, 3, 5, 6),
(24, 4, 5, 7),
(25, 5, 5, 5),
(26, 1, 7, 4),
(27, 2, 7, 8),
(28, 3, 7, 6),
(29, 4, 7, 8),
(30, 5, 7, 4),
(31, 1, 8, 4),
(32, 2, 8, 4),
(33, 3, 8, 4),
(34, 4, 8, 4),
(35, 5, 8, 4),
(36, 1, 10, 7),
(37, 4, 10, 7),
(38, 5, 10, 7),
(39, 2, 9, 7),
(40, 5, 9, 4),
(41, 1, 11, 4),
(42, 2, 11, 7),
(43, 4, 11, 7),
(44, 5, 11, 7);

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

DROP TABLE IF EXISTS `student`;
CREATE TABLE IF NOT EXISTS `student` (
  `StudentID` int(11) NOT NULL AUTO_INCREMENT,
  `NJITStudentID` varchar(12) NOT NULL,
  `DocNumber` varchar(45) NOT NULL,
  `CreateTime` datetime NOT NULL,
  `LastUpdateTime` datetime NOT NULL,
  PRIMARY KEY (`StudentID`),
  UNIQUE KEY `UNQ_NJITStudentID` (`NJITStudentID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Stores information about all students' AUTO_INCREMENT=7 ;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`StudentID`, `NJITStudentID`, `DocNumber`, `CreateTime`, `LastUpdateTime`) VALUES
(1, '11122333', '1', '2011-06-02 12:35:56', '2011-06-02 12:35:56'),
(2, '11133444', '2', '2011-06-02 12:36:11', '2011-06-02 12:36:11'),
(3, '11144555', '3', '2011-06-02 12:36:20', '2011-06-02 12:36:20'),
(4, '11155666', '4', '2011-06-02 12:36:33', '2011-06-02 12:36:33'),
(5, '11166777', '5', '2011-06-03 13:07:34', '2011-06-03 13:07:34'),
(6, '11177888', '8', '2011-06-03 13:17:40', '2011-06-03 13:17:40');

-- --------------------------------------------------------

--
-- Table structure for table `studenttoberated`
--

DROP TABLE IF EXISTS `studenttoberated`;
CREATE TABLE IF NOT EXISTS `studenttoberated` (
  `StudentID` int(11) NOT NULL,
  `RatingSessionID` int(11) NOT NULL,
  `NumRatings` int(11) NOT NULL DEFAULT '1',
  `RatingCount` int(11) NOT NULL DEFAULT '0',
  `AdjReqd` bit(1) NOT NULL DEFAULT b'0',
  `AdjDone` bit(1) NOT NULL DEFAULT b'0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `studenttoberated`
--

INSERT INTO `studenttoberated` (`StudentID`, `RatingSessionID`, `NumRatings`, `RatingCount`, `AdjReqd`, `AdjDone`) VALUES
(1, 1, 2, 3, b'1', b'1'),
(2, 1, 2, 3, b'1', b'1'),
(3, 1, 2, 3, b'1', b'1'),
(4, 1, 2, 1, b'0', b'0'),
(5, 1, 2, 0, b'0', b'0'),
(6, 1, 2, 0, b'0', b'0');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `UserID` int(11) NOT NULL AUTO_INCREMENT,
  `UserName` varchar(60) NOT NULL,
  `Password` varchar(20) NOT NULL,
  `IsAdmin` bit(1) NOT NULL DEFAULT b'0',
  `CreateTime` datetime NOT NULL,
  `LastLoginTime` datetime NOT NULL,
  `IsActive` bit(1) NOT NULL DEFAULT b'1',
  PRIMARY KEY (`UserID`),
  UNIQUE KEY `UNQ_UserName` (`UserName`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Defines the users of the system, whether admin or rater.' AUTO_INCREMENT=83 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`UserID`, `UserName`, `Password`, `IsAdmin`, `CreateTime`, `LastLoginTime`, `IsActive`) VALUES
(1, 'rsb24@njit.edu', 'gewbgttl', b'1', '2010-03-23 12:27:13', '2011-06-07 00:00:00', b'1'),
(2, 'elliot@adm.njit.edu', 'password', b'1', '2010-05-12 21:26:42', '2011-05-17 00:00:00', b'1'),
(3, 'tress@adm.njit.edu', 'password', b'1', '2010-05-12 21:26:42', '2010-05-13 00:00:00', b'1'),
(28, 'klobucar@njit.edu', 'ge', b'0', '2010-04-19 00:00:00', '2011-04-13 00:00:00', b'1'),
(29, 'erich@njit.edu', 'eric', b'0', '2010-05-13 00:00:00', '2010-05-13 00:00:00', b'1'),
(30, 'jonathan.curley@njit.edu', 'blueflower', b'0', '2010-05-13 00:00:00', '2011-04-15 00:00:00', b'1'),
(31, 'john.n.esche@njit.edu', 'EAGJEsche5', b'0', '2010-05-13 00:00:00', '2010-05-13 00:00:00', b'1'),
(32, 'scharf@njit.edu', 'winston', b'0', '2010-05-13 00:00:00', '2011-02-09 00:00:00', b'1'),
(33, 'rolanne.henry', 'here', b'0', '2010-05-13 00:00:00', '2010-05-13 00:00:00', b'1'),
(34, 'steffen@njit.edu', 'drake9511', b'0', '2010-05-13 00:00:00', '2010-05-13 00:00:00', b'1'),
(35, 'rolanne.henry@njit.edu', 'here', b'0', '2010-05-13 00:00:00', '2011-02-09 00:00:00', b'1'),
(36, 'donahue@njit.edu', 'portfolio', b'0', '2010-05-13 00:00:00', '2011-02-09 00:00:00', b'1'),
(37, 'thunt@njit.edu', 'theresa15', b'0', '2010-05-13 00:00:00', '2010-05-13 00:00:00', b'1'),
(38, 'jegan', '5896', b'0', '2010-05-13 00:00:00', '2010-05-13 00:00:00', b'1'),
(39, 'jegan@njit.edu', '5896', b'0', '2010-05-13 00:00:00', '2010-05-13 00:00:00', b'1'),
(40, 'mascarel@njit.edu', 'Olivia12', b'0', '2010-05-13 00:00:00', '2010-05-13 00:00:00', b'1'),
(41, 'elliot@njit.edu', 'ge', b'0', '2010-05-13 00:00:00', '2010-05-13 00:00:00', b'1'),
(42, 'huey@njit.edu', 'sugaree', b'0', '2010-05-13 00:00:00', '2011-02-09 00:00:00', b'1'),
(43, 'john.n.esche', 'EAGJEsche5', b'0', '2010-05-13 00:00:00', '2010-05-13 00:00:00', b'1'),
(44, 'paris@njit.edu', 'paris6', b'0', '2010-05-13 00:00:00', '2011-02-09 00:00:00', b'1'),
(45, 'pardi@adm.njit.edu', 'marchnina', b'0', '2010-05-13 00:00:00', '2010-05-13 00:00:00', b'1'),
(47, 'cjohnson@njit.edu', 'happy', b'0', '2010-05-13 00:00:00', '2011-02-11 00:00:00', b'1'),
(48, 'thunt@yahoo.com', 'theresa15', b'0', '2010-05-13 00:00:00', '2010-05-13 00:00:00', b'1'),
(49, 'pardi@njit.edu', 'marchnina', b'0', '2010-05-13 00:00:00', '2010-05-13 00:00:00', b'1'),
(50, 'john.n.esche.njit.edu', 'EAGJEsche5', b'0', '2010-05-13 00:00:00', '2010-05-13 00:00:00', b'1'),
(51, 'cjohnson', 'happy', b'0', '2010-05-13 00:00:00', '2010-05-13 00:00:00', b'1'),
(52, 'scharf', 'winston', b'0', '2010-05-13 00:00:00', '2011-02-09 00:00:00', b'1'),
(53, 'hhuey', 'sugaree', b'0', '2010-05-13 00:00:00', '2010-05-13 00:00:00', b'1'),
(57, '', '', b'0', '2011-02-01 00:00:00', '2011-05-17 00:00:00', b'1'),
(58, 'friedman@njit.edu', 'liminal55', b'0', '2011-02-09 00:00:00', '2011-02-09 00:00:00', b'1'),
(59, 'mascarel', 'Olivia14', b'0', '2011-02-09 00:00:00', '2011-02-09 00:00:00', b'1'),
(60, 'mascarel@adm.njit.edu', 'Olivia14', b'0', '2011-02-09 00:00:00', '2011-02-09 00:00:00', b'1'),
(61, 'john_n_esche@admin.njit.edu', 'eschetiz', b'0', '2011-02-09 00:00:00', '2011-02-09 00:00:00', b'1'),
(62, 'johnesche@admin.njit.edu', 'eschetix', b'0', '2011-02-09 00:00:00', '2011-02-09 00:00:00', b'1'),
(63, 'esche@njit.edu', 'eschetix', b'0', '2011-02-09 00:00:00', '2011-02-09 00:00:00', b'1'),
(64, 'henry@njit.edu', 'read', b'0', '2011-02-09 00:00:00', '2011-02-09 00:00:00', b'1'),
(65, 'dalal@njit.edu', 'marigold', b'0', '2011-02-09 00:00:00', '2011-02-09 00:00:00', b'1'),
(66, 'donahue', 'Humreading', b'0', '2011-02-09 00:00:00', '2011-02-09 00:00:00', b'1'),
(67, 'fleische', 'frieda29', b'0', '2011-02-09 00:00:00', '2011-02-09 00:00:00', b'1'),
(68, 'fleische@njit.edu', 'frieda29', b'0', '2011-02-09 00:00:00', '2011-02-09 00:00:00', b'1'),
(69, 'paris@njit.edu.', '', b'0', '2011-02-09 00:00:00', '2011-02-09 00:00:00', b'1'),
(71, 'sharla.l.sava@njit.edu', 'ge', b'0', '2011-02-09 00:00:00', '2011-02-10 00:00:00', b'1'),
(72, 'klobucar2njit.edu', 'pass', b'0', '2011-02-09 00:00:00', '2011-02-09 00:00:00', b'1'),
(73, 'sava@njit.edu', 'Henry1936', b'0', '2011-02-10 00:00:00', '2011-02-10 00:00:00', b'1'),
(74, 'jcurley', 'blueflower1', b'0', '2011-04-14 00:00:00', '2011-04-14 00:00:00', b'1'),
(75, 'jonathan.r.curley@njit.edu', 'blueflower1', b'0', '2011-04-14 00:00:00', '2011-04-14 00:00:00', b'1'),
(76, 'elliot@adm,njit,edu', 'imagine', b'0', '2011-04-15 00:00:00', '2011-04-15 00:00:00', b'1'),
(77, 'elliot@adm,njit.edu', 'imagine', b'0', '2011-04-15 00:00:00', '2011-04-15 00:00:00', b'1'),
(78, 'les.admin@njit.edu', 'password', b'1', '2011-04-17 20:49:33', '2011-04-18 00:00:00', b'1'),
(79, 'les@njit.edu', 'password', b'0', '2011-04-17 20:49:33', '2011-04-17 20:50:11', b'1'),
(80, 'elliot@adm.njit,edu', 'imagine', b'0', '2011-05-17 00:00:00', '2011-05-17 00:00:00', b'1'),
(81, 'harry@njit.edu', 'ge', b'0', '2011-06-03 00:00:00', '2011-06-07 00:00:00', b'1'),
(82, 'sarah@njit.edu', 'ge', b'0', '2011-06-03 00:00:00', '2011-06-07 00:00:00', b'1');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `competencytovalues`
--
ALTER TABLE `competencytovalues`
  ADD CONSTRAINT `fk_CompetencyToValues_Competency1` FOREIGN KEY (`CompetencyID`) REFERENCES `competency` (`CompetencyID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_CompetencyToValues_CompetencyValue1` FOREIGN KEY (`CompValueID`) REFERENCES `competencyvalue` (`CompValueID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_CompetencyToValues_CompExtendedDescription1` FOREIGN KEY (`ExtendedID`) REFERENCES `compextendeddescription` (`ExtendedID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `compextendeddescription`
--
ALTER TABLE `compextendeddescription`
  ADD CONSTRAINT `fk_CompExtendedDescription_CompetencyValue1` FOREIGN KEY (`CompValueID`) REFERENCES `competencyvalue` (`CompValueID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `ratingsession`
--
ALTER TABLE `ratingsession`
  ADD CONSTRAINT `fk_RubricID` FOREIGN KEY (`RubricID`) REFERENCES `rubric` (`RubricID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
