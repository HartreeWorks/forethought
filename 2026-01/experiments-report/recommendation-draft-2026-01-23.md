# Recommendation draft

*23 January 2026*

## The motivating question

In the project plan and mid-sprint plan, I

For this moonshot exploration, the key question is: **Should Forethought significantly increase investment in AI adoption during 2026** (e.g. by hiring a contractor for 3+ months, or seeking a full-time hire, or )?

Another way to put this: are there major returns to having specialised expertise on the team, as opposed to just having researchers wing it in their use of AI.

As I see it, there are two ways an uplift specialist could support your researchers:

1. **Building mundane affordances** — interfaces, workflows, filtering
2. **Extracting more intelligence** — via advanced prompt engineering and orchestration

This week I've been trying to get a sense of the returns we might expect from the latter.

## What I did

### The grader question

The first question was: how hard is it to systematically iterate on prompting techniques, and what might the returns be?

To get a read on this, I wanted to understand what it would take to build a useful automated grader that could enable systematic prompt iteration without requiring tons of Forethought researcher time to evaluate outputs.

Ideally, we'd build a Forethought-specific grader, validated by having your researchers make their own quality judgments and comparing them against the grader's outputs. I didn't want to invest in that this week. Instead, I used the grader from the ACORN benchmark, which has been validated against human judgment by the ACORN team and is a reasonable proxy for the Forethought use case.

So we have some reason to trust this grader as a means to evaluate prompt outputs at scale.

### The experiments

Taking that grader, I generated 9 different brainstorm-critique prompt variations and ran their outputs through the grader to get a sense of the variation in quality.

I also did some pairwise comparison myself to see whether I agreed that the outputs the grader considered best were clearly better than those it considered worst. There was agreement—a sanity check that the grader is tracking something real.

### The deep research

As I was running these experiments, I realised I should probably just do deep research on what other people are finding about returns to systematic prompt evaluation. We should form our judgment based on what others have already figured out. My day or two of experiments is helpful, but others have likely already answered this question or close enough questions that we can make the inference.

So I did that. And basically, yeah—it does look like we could expect pretty major returns.

## My recommendation

The combination of:
- Pretty predictable returns to helping researchers build mundane affordances
- Pretty high expected returns from more moonshot-style prompt engineering and orchestration

...means that **Forethought should hire a full-time AI uplift contractor this year**. I think they should basically do that.

The next step would be to hire somebody for three months and see how it goes. I'm a pretty ideal candidate, but you should definitely search to find others too.

## My situation

My quick take is that I want to continue spending roughly half my time on the AI Wow newsletter and blog. My default plan is 50% client contracting, 50% newsletter/blog and other public goods work. I want to keep that pattern until I find such a promising client project that I should obviously go all-in (80-90% time).

**What I'd be excited to do for you:** Commit to roughly 6 weeks full-time, spread over the next three months, and see how far we get. If I get extremely excited, I can dial up the Forethought work and dial down other things.

One thing: I'm very flexible. We could pencil in 6 weeks of my time over the next three months, and I'll just do stuff. If either of us feels like it's not yielding good returns, we figure out how to fix that or I move on. I have no need for stable employment or commitment from your side. It would be a feature, not a bug, to operate with this understanding of flexibility—there's a default plan, but I don't want to spend time on things that aren't obviously wildly useful.

## How good is this compared to alternatives?

I think this is plausibly among the best things I could work on. One worry: some of your research doesn't quite resonate or motivate me as much as I wish it did. I have some uncertainty about the returns to doing this research right now. I want a more visceral sense of how this research will convert into practical decision-making.

That said, I do basically think that advancing human knowledge on the philosophy and economics of AI is one of the most important things to do right now. So I'm pretty excited about this.

**Main alternatives I'd be similarly or more excited by:**
- AI uplift for technical AI safety research (e.g. Apollo, Redwood)
- People working on AI safety policy
- Someone closer to government (e.g. Dean Ball)
- UK/US AISI
- AI safety grantmaking (could be a bottleneck given Anthropic money coming online)
- Matt Clifford for Prime Minister team

## What if Forethought shouldn't hire me?

If you don't hire me or someone similar, you should basically ask Fin to spend a third of his time really promoting AI uplift at the organisation. Honestly, I think he should do this even if I'm around.

## What would help me decide?

- A sketch of what I'd actually do over 6-12 weeks
- A bit more sense of working with your researchers—doing a few more uplift calls, building more skills for them
- Getting a sense of whether this would push the needle in an enduring way, as opposed to factual research that researchers could figure out for themselves
