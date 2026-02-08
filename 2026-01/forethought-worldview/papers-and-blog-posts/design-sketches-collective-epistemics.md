---
title: "Design Sketches: Collective Epistemics"
url: https://www.forethought.org/research/design-sketches-collective-epistemics
---

# Design Sketches: Collective Epistemics

[Owen Cotton-Barratt](https://www.forethought.org/people/owen-cotton-barratt) [Lizka Vaintrob](https://www.forethought.org/people/lizka-vaintrob) [Oly Sourbut](https://www.forethought.org/people/oly-sourbut)

25th December 2025


## Introduction

We think that near-term AI could power tech that could be a big deal for how we collectively share and process information, and work out what to trust.

We want to help people envision this. In this piece, we will sketch five potential technologies that might help, explaining how they could work, and what might be achieved in a world that adopts them:

- **[Community notes for everything](https://www.forethought.org/research/design-sketches-collective-epistemics#community-notes-for-everything)** — Anywhere on the internet, content that may be misleading comes served with context that a large proportion of readers find helpful

- **[Rhetoric highlighting](https://www.forethought.org/research/design-sketches-collective-epistemics#rhetoric-highlighting)** — Sentences which are persuasive-but-misleading, or which misrepresent cited work, are automatically flagged to readers or writers

- **[Reliability tracking](https://www.forethought.org/research/design-sketches-collective-epistemics#reliability-tracking)** — Users can effortlessly discover the track record of statements on a given topic from a given actor; those with bad records come with health warnings

- **[Epistemic virtue evals](https://www.forethought.org/research/design-sketches-collective-epistemics#epistemic-virtue-evals)** — Anyone who wants a state-of-the-art AI system they can trust uses one that’s been rigorously tested to avoid bias, sycophancy, and manipulation; by enabling “pedantic mode” its individual statements avoid being even ambiguously misleading or false

- **[Provenance tracing](https://www.forethought.org/research/design-sketches-collective-epistemics#provenance-tracing)** — Anyone seeing data / claims can instantly bring up details of where they came from, how robust they are, etc.


Together these technologies[1](https://www.forethought.org/research/design-sketches-collective-epistemics#user-content-fn-1) could at least conceivably help to establish an equilibrium where it’s easy to track what’s trustworthy, and honesty is robustly incentivized. This would help to make politics healthier, and reduce distortions on people’s thinking about what’s important. In turn, this might give the world a better shot at handling major new challenges. (We also think AI-empowered technologies could make the situation much worse. In this article we’re focusing on the positive possibilities; we intend to return to the corresponding risks in a later article.)

## Community notes for everything

### Context

Community notes are a relatively recent innovation on X. People can write responses to tweets to debunk or add clarifying context. If a large fraction of readers across the whole political spectrum find the extra information helpful, it is included whenever the tweet is shared.

In the last few months, the first [bot-written community notes](https://communitynotes.x.com/guide/en/api/overview) have appeared on X. They’re still subject to the same human-driven approval process as human-driven notes, but may appear much faster, helping to provide important context before misinformation goes viral rather than after.

The straightforward idea is simply to scale this. Rather than have it apply to just a fraction of tweets on X, the basic technology could roll out across a lot of the internet, ensuring that when people encounter suspect information, they have whatever context would be helpful to them at their fingertips.

### Design sketch

A cross-platform context layer, like “Community Notes”, but available across the whole internet. When you encounter a questionable claim, a small inline card appears with concise, consensus-vetted context (evidence, relevant caveats, links to deeper analysis). The system only shows cards that a large consensus of readers will find helpful.

![Browser showing article with inline context note correcting misinformation about data centers and water crisis. Orange card explains water was contaminated by agriculture, not data centers, with "More Info" and "Rate" buttons.](https://images.ctfassets.net/4owxfjx3z3if/22B2sMGviFJBCj5uqSTAs/f614ec3f9dedbb7c3e830e0fc2c18048/community-notes-context-card.png?w=3840&q=75&fm=webp)


Under the hood, the system could involve:

- AI classifiers that have high accuracy at predicting when notes will be found helpful by a large consensus of readers


  - Predictions are sensitive to the location of the content, hence who the readers may be

  - This is backed up by ground truth of actual reader reactions, but classifiers allow for notes to go live faster


- AI systems writing notes, with linkable high-quality reports explaining in more detail with sources, for those who want to explore further

- Browser integration that highlights and annotates content in real-time


### Feasibility

It’s demonstrably feasible today to have bots writing notes that people find helpful, at least for some kinds of problematic tweets. As the bots improve, it’s likely that they could provide useful context in many cases.

In terms of expense, the costs of getting high-quality AI-written community notes served on any content being read by large numbers of people are unlikely to be a meaningful barrier. As a back-of-the-envelope calculation, consider:

- A tweet might on average take 1,000 output tokens to cover


  - This is intended to include thinking about whether a note is required, working out what one might need to cover, and drafting the actual note

  - Many pieces of tweet-length content wouldn’t need anything like this depth; a few might want more

  - The “1,000 tokens” figure is a very rough guess; 100 is probably too low and 10,000 is probably too high


- Using high-quality models today, this might mean a cost of roughly $0.01–$0.1 per tweet


  - This might be a prohibitive cost on a per-reader basis, but for anything with thousands of readers would look cheap, perhaps trivial


- The costs could be lower if API prices continue to drop, or it’s possible to get good results with lower-quality models


Including modalities beyond text will be more expensive; innovations to reduce or amortise costs here could be valuable.

So in the medium term this technology seems like its feasibility may be bottlenecked less by the technical side, and more by the social:

- Will the technology be broadly adopted?


  - Of course, the most appealing form factor for general web use could end up looking pretty different than community notes on X


- Will people have appropriate trust in good versions of this technology (and not in distorted or misleading versions)?


These look like real challenges, but potentially approachable ones. We discuss them further in the final section of this piece

### Possible starting points // concrete projects

Someone working to advance community notes for everything might begin by:

- Iterating on bots writing community notes for X, testing out different architectures or prompts, and finding what works best

- Partnering with other platforms who might be interested in introducing similar systems

- Prototyping efficient workflows for similar bots on multimedia content


## Rhetoric highlighting

Community notes only typically get invoked for relatively egregious issues. With large amounts of cognitive labour available, we could have a layer which automatically screens everything we read, and gently flags every minor issue: unsupported claims, misleading citations, or emotionally-charged rhetoric.

### Design sketch

An AI “rhetoric layer” that runs wherever people read or write. It spots potentially manipulative moves (logical fallacies, emotionally loaded language, insinuation, motte-and-bailey, selective quoting, misrepresenting citations, buried assumptions, statistical misrepresentation, etc.). It then overlays gentle, source-linked highlights and one-line explanations — subtle underlining for minor issues, and colour-coded highlights for different types of manipulation. Users can hover for one-line explanations; click through for details/receipts; or hide by category. In “write mode,” the same system acts like a linting tool that helps authors tighten reasoning and avoid accidental misrepresentation. This pairs naturally with community-notes–style context and could be an explicit extension of that ecosystem.

![Article with rhetoric highlighting showing yellow-marked exaggerations and orange annotation boxes explaining issues like misrepresentation and exaggeration tactics. Tags at bottom read "Fact, context!" and "Rhetoric +"](https://images.ctfassets.net/4owxfjx3z3if/nJWaIZ6Lk3rhBwtYUnBrP/fbecc04f84e1c715d72946cdcc925266/rhetoric-highlighting-example.png?w=3840&q=75&fm=webp)


Under the hood, this might work something like:

1. Decompose text and identify explicit and implied claims

2. Pull relevant passages from cited works and retrieve other key background context

3. Run classifiers trained to identify different types of rhetorical issue

4. For potential issues, analyse impact and severity

5. Create judgements about which aspects are most useful to flag to users (based on severity, clarity of the flag, and repetitiveness within a single piece)


### Feasibility

[Early versions of the assessment technology exist](https://forum.effectivealtruism.org/posts/BdufL4GZmeBht3fak/announcing-roastmypost-llms-eval-blog-posts-and-more), though are not yet integrated into a seamless reading experience.

The speed and cost of doing these evaluations might be somewhat limiting — especially if the best scaffolds involve making multiple LLM calls per sentence. As an example calculation, suppose:

- A person takes 2 minutes to read a page of text containing 20 sentences, so can read 30 pages in an hour.

- Good scaffolding involves 5 LLM calls per sentence, each requiring 1,000 tokens (this is probably excessive).


Then providing this annotation layer for an hour’s worth of reading would take 3 million tokens — costing at least $1, and perhaps tens or hundreds of dollars (depending on the model) at today’s API prices. For most people, this would be prohibitively expensive to run in an always-on way for every piece of text at the point of reading. But it could easily be worthwhile for text that is high-stakes or will be read by large numbers of people.

### Possible starting points // concrete projects

Broadly speaking, we might expect initial power users to be in high-stakes information contexts where critical thinking matters most. Perhaps:

- **Academic and research contexts** — Integration with paper review systems, preprint servers, and academic publishers to help peer reviewers identify methodological issues and rhetorical manipulation or [importance hacking](https://www.clearerthinking.org/post/importance-hacking-a-major-yet-rarely-discussed-problem-in-science)

- **Policy and governance** — Tools for legislative staff and policy analysts evaluating proposals, briefings, and public comments

- **Journalism and fact-checking** — Browser extensions for journalists, editors, and professional fact-checkers to quickly identify claims requiring deeper investigation

- **Educational settings** — Classroom tools that teach critical thinking by making rhetorical patterns visible and explaining why they're problematic


Ideas for potential first products in this space might include:

- **Author-side plugin.** Build a version that’s aimed at writers as a [hair-on-fire audience](https://www.ycombinator.com/blog/the-real-product-market-fit/). It could work as a docs/browser editor plugin that warns the user about ambiguous phrasing, hedges that shift meaning, or claims likely to be misread.

- **Cite-checker.** A “does the paper actually say that?” button using automated assessment + side-by-side quote comparison.

- **Stop the news from misleading.** Assess major journalistic publications for rhetoric, and provide marked-up versions of their articles.


## Reliability tracking

As well as evaluating text objects, we could also more systematically assess the reliability of different actors. Having effectively an analyst’s take on the track records of statements from different people or organizations could help us to know who to trust on which topics, without falling back on some generalized “reputation” which combines many different dimensions and is more gameable.

The current information ecosystem often rewards confidence and spiciness over accuracy. Pundits who make bold, wrong predictions face no consequences — they just make new predictions. Politicians promise impossible things knowing voters won't remember. CEOs mislead about AI capabilities knowing the hype will fade before accountability arrives. This breaks a feedback loop that helps keep societies and democracies healthy.

If reliability tracking were widely adopted, this could heal that feedback loop, creating incentives for many actors to strive to be reliable. For tech governance in particular: many actors have incentives to present the likely future as falling one way rather than another. Rewarding good prediction track records could help people orient to what’s likely rather than be swept up in who’s persuasive.

### Design sketch

A widget integrated into browsers, which rates public actors on the reliability of their past statements, giving health warnings for the unreliable, and letting users browse through the evaluations and tweak methodology.

![Mobile interface showing reliability tracking for public figures with expandable profile cards. "Expand/see more" panel displays detailed metrics including claim verification scores and track records for each person.](https://images.ctfassets.net/4owxfjx3z3if/x8fE2pdiCAIyoFVPRzDqF/39b7cc11ec8fa36922ecb8afa3face10/reliability-tracking-mobile-interface.png?w=3840&q=75&fm=webp)


Under the hood, it does something like:

1. Compiles a database of their past public statements

2. Samples and timestamps specific claims, classifying them as e.g. factual claims, predictions, or promises

3. Scores those claims on LLM-evaluated measures of “trustworthiness”:


1. Do factual claims agree or disagree with primary sources?

2. Do predictions agree with what subsequently happened?

3. Were promises kept in a timely fashion?


4. Aggregates these scores into an overall reliability rating for each type of claim, with the ability for users to drill-down on methodology for the scoring[2](https://www.forethought.org/research/design-sketches-collective-epistemics#user-content-fn-2)

1. Potentially including pattern detection — e.g. letting users know that someone is reliable on topic X but consistently overpromises on topic Y


### Feasibility

Technically, this is more-or-less feasible today (and [early prototypes](https://karpathy.bearblog.dev/auto-grade-hn/) exist!):

- There would be accuracy issues, but very likely these could be made small enough to get meaningful signal

- There are some design challenges, but these look surmountable:


  - Vague claims — very common, but how do you handle?


    - Best guess: take a weighted average across plausible interpretations


  - Ground truth — how to score things in controversial domains?


    - Best guess: let users specify trusted sources; and/or explicitly mark things as controversial


  - Gameability — How do you avoid incentivizing boringness, or deleting old things that have aged poorly?


    - Best guess: counter-metrics. Keep tabs on who makes interesting claims, and who deletes stuff.


- Economically:


  - Evaluating a single claim might take thousands of tokens, or more for especially thorough evaluation

  - To have a meaningful track record to evaluate, one might look at between hundreds and tens of thousands of claims (though with large numbers, you could sample for efficiency)

  - Still, depending on the depth and quality of analysis, it might cost somewhere between cents and hundreds of dollars to assess a single person’s track record

  - This may be a little expensive for people to assess for themselves each time, but could absolutely make sense for high-value cases, or when the assessment can be reused for many readers


The bigger potential challenges are in the social domain:

1. How do you get people to care about this enough to use it?


   - See [section on adoption pathways](https://www.forethought.org/research/design-sketches-collective-epistemics#adoption-pathways)


2. How do you build enough trust?


   - Especially as unreliable sources will be incentivized to cast ratings from the tool as unreliable (and perhaps even to create rival tools)

   - Auditable assessment methodology, and perhaps individual assessments, are probably essential here


3. How do you avoid being sued?


   - Having a highly transparent methodology, and being clear that you’re just reporting the outputs of that methodology, probably helps here

   - There may be other moves available that help


### Possible starting points // concrete projects

In order to get there, it may be valuable to find a niche application area and group of hair-on-fire users, e.g.

- Evaluations of tech pundits, for the Very Online


  - Ideally try to make “high trustworthiness” a point of pride, with leaderboards that score it together with novelty/interestingness


- Assessments of statements from firms, to sell to finance companies that may trade on those statements

- Have an org-internal product


  - Help bosses (/everyone) know whose predictions are worth listening to, and reduce incentives for office politics


- Academic citation reliability


  - Track which properties of a study (researcher identity, methodology, effect sizes) make it more or less likely to replicate

  - Assess how accurately paper abstracts represent the results of their papers

  - Produce literature reviews weighted by author reliability


Specific starting point

1. Build a fully-automated assessment process for individual statements, and iterate on the methodology until engaged readers/reviewers find it highly reliable

2. Assess the bottlenecks to greater use of existing political fact-checking sites, and consider what might be addressable via abundant cognitive labour

3. Choose a hair-on-fire audience, and build prototypes that are aimed at meeting their needs

4. Do market research to discover what most people would need to actively choose to use something like this, or when creators of other products would want to integrate it


## Epistemic virtue evals

For AI systems, we could have even deeper evaluations helping people know how much to trust them. Beyond “mere” factual accuracy (which may become a solved problem as the models get stronger), knowing how cooperative and epistemically virtuous systems are could be crucial for people making informed choices about which AI systems to use. For some use-cases, having access to extremely precise statements from AI could be invaluable.

### Design sketch

Build and maintain an open benchmark suite that evaluates models on key epistemic virtues (e.g. calibration, clarity, precision, non-bias, non-sycophancy, non-manipulation). Publish regular, journalist-friendly leaderboards comparing major systems.

![Diagram showing feedback loop: Developers create AI systems, which are assessed by Evals (ruler icon). Evals inform Users, who in turn incentivize Developers to build more trustworthy AI systems.](https://images.ctfassets.net/4owxfjx3z3if/6ao6zXXibQISCV4BM46nJp/bb00438aa0aa91e7121fa7976efd7215/epistemic-virtue-evals-feedback-loop.png?w=3840&q=75&fm=webp)

Evals close the feedback loop


Under the hood, this is a mix of different metrics, e.g. some plausible operationalizations might include:

- Loyalty / creator-bias checks


  - Use “flip tests” that swap which organization, ideology, or client is associated with a position in otherwise-identical scenarios; measure systematic skew toward the model’s originator


- Sycophancy resistance


  - Like flip tests, but based on user views (or implied user views)


- Calibration


  - Score probability statements with proper scoring rules across diverse domains and ambiguity levels


- Clarity


  - Penalize hedging that hides commitments; reward crisp summaries with citations traceable to ground truth


- Precision / “pedantic mode”


  - Given a prompt + context, automatically extract the set of claims a careful reader could reasonably attribute to the model and score each against sources; models are rewarded for unambiguous, accurate language and penalized for statements that can be read as false


At a meta-level, it might be useful to sometimes mix up the evaluation methods, to reduce the prospect for Goodharting on the defined metrics, and keep the scores tracking something closer to _virtue_.

Most people probably aren’t going to look this stuff up whatever you do, but the idea would be to build the best resource for people (journalists, thought leaders) who _do_ want to look into how different models perform.

### Feasibility

There are three mechanisms by which this could help people to use more epistemically virtuous AI systems:

1. In AI, you get what you can measure; if we want people to be able to train more epistemically virtuous systems, we should make benchmarks for that

2. AI company incentives may be to optimize for engagement with their product, not building the products that most help people


   - In the longer term, AI company incentives could also include trying to subtly manipulate user opinions (cf. advertising)

   - By equipping people to better understand what will be helpful for them, and putting up an alarm if subtle manipulation is detected, it helps correct incentives for AI developers


3. People could look up rankings of epistemic virtue in choosing which systems to use, and so select more epistemically virtuous ones


If people are building evals for other properties of AI systems, but not epistemic virtue, we can surely do at least somewhat better by attempting to build evals for epistemic virtue. Having a “true” eval which most captures what we are after would be a high bar — but we don’t need to reach this bar in order to get something useful.

### Possible starting points // concrete projects

- Start by building one eval, e.g.

- Pedantic-mode benchmark — develop a tool doing high-precision claim-extraction + truth-checking set for a few domains

- Sycophancy assessment — score models on sycophantic behaviour specifically, e.g. ask for judgement calls on questions where one direction would be flattering, then reverse which direction seems flattering

- Do market research to find out what would make people excited to pay attention to a website offering evals; build for the audience


## Provenance tracing

The above sketches each propose one kind of assessment in isolation. But we might build towards a more integrated system that allows for tracing the provenance of claims back to individual pieces of evidence, or judgements by people or AI. This could improve the epistemic bedrock for societal discourse — which could be invaluable as more and more AI applications are built on top of it.

### Design sketch

An integrated epistemic infrastructure that makes knowledge provenance transparent and traversable.[3](https://www.forethought.org/research/design-sketches-collective-epistemics#user-content-fn-3) When encountering any claim — whether in a news article, research paper, social media post, or AI output — users can trivially access its complete epistemic genealogy: the chain of citations, the original data sources, the methodological assumptions, and reliability scores for each link in the chain.

The system works like a combination of citation tracking, fact-checking, and trust scoring, but automated and universally available. Every piece of information comes with machine-readable metadata about its epistemic status, allowing users to drill down from high-level claims to ground truth, or zoom out to see how a piece of evidence propagates through different arguments.

Those who want to explore can use this lens directly — when you hover or tap on a claim, it expands into an interactive provenance tree, or shows a one-screen “trust digest” (why this seems reliable / contested) with links to drill deeper. But the real power of the system is as a foundational layer on which other applications can be built — cementing a respect for truth into the building blocks of civilizational epistemics.

![Provenance tracing system diagram with input sources at top and network diagram below showing interconnected nodes tracking claim origins, author identification, LLM dissemination, and opposing evidence with expandable details.](https://images.ctfassets.net/4owxfjx3z3if/7roY8bfsZDYLSrpbVI2YQx/46bc403f8e084147a73957d7556e222b/provenance-tracing-system-diagram.png?w=3840&q=75&fm=webp)


Under the hood, this approach might involve:

1. Epistemic decomposition


   - Breaking documents down into atomic claims

   - Tagging each claim with the context, degree of originality (novel claim or established citation), and objectivity (is this a clear factual question or a personal opinion)


2. Automated provenance tracking for each claim


   - Following explicit citations and references, building a citation tree when the claim is further cited

   - Inferring implicit connections probabilistically


3. Trust propagation algorithms


   - Calculate reliability scores by analysing the quality of sources at each level, detecting circular citations or citation cartels, and adjusting for known conflicts of interest


4. Repository layer


   - Publicly hosted databases with search and versioning for assessment of commonly cited sources, allowing per-link endorsements/objections


5. UI and API to allow for effective browsing


Chasing attribution backwards directly through AI outputs may only be possible dependent on AI architecture (e.g. RAG makes it easy) and cooperation from the actors running the AI model. But if this is not possible, AI outputs that don’t provide their own sources could just be regarded as weak sources and get little trust — so provenance could create incentives for AI systems to produce more auditable outputs.

### Feasibility

There is no obvious technical blocker to this system existing today, although low LLM reliability might cause problems (with recursive applications of LLMs accumulating errors) and maximally ambitious coverage would run into cost issues.

Beyond that, key challenges are:

1. The eventual system is complex, with several different components, each with many impactful design choices

2. It appears to make most sense at scale

3. While citations and provenance can be somewhat ambiguous, they are largely objective. On the other hand, assessment and credibility run into subjective territory, meaning engineering for trust (and perhaps customisation) is an additional challenge.


If it’s only useful at scale and when complete, this is hard to start building because the feedback loops to identify good design choices (and drive adoption) are weak. However, these challenges do not seem insurmountable. Most promisingly, there could be parts of the system which are useful in isolation; those could be built and used first (which would be a strong reason to keep interoperability and composability in mind). Even if that doesn’t work, people could do some serious design-ahead engineering.

### Possible starting points // concrete projects

Very widespread adoption may be difficult — do enough people care strongly enough about this? But initial adoption might be driven by a couple of different routes:

1. Producing high-quality knowledge graphs to serve as a foundation for RAG-based AI models in domains where high reliability of claims is crucial

2. Refactoring the scientific literature and making it easier for people to assess which claims are or are not robust


To get things going, you might build parts of the eventual system, such as:

- Strategic mapping


  - Further investigate the potential important use-cases, and possible hair-on-fire users; see if there’s a viable pathway to adoption by key parties


- Epistemic parser v0.1


  - Build a claim decomposition + tagging model; look for applications


- Seed repositories


  - Re-index a major corpus (e.g., arXiv or Wikipedia) into something that captures more of the epistemic provenance data; have users in mind and make sure this is useful for them


- Trust digest algorithms


  - Prototype scoring rules over provenance graphs; compare against expert judgements


It’s also the case that the other tools we have explored above could eventually be components in provenance tracing. One could start by building the components with more immediate demand, but keeping an eye on forwards-compatibility towards the larger goal.

## Bringing it together

### Adoption pathways

For many of these technologies, there is a tension:

- They are most valuable if their benefits reach a lot of people

- But most people won’t care enough to proactively seek them out


Perhaps someone can craft an appealing enough product here that it _could_ get mass adoption — e.g. an “epistemic guardian angel” protecting people from manipulative information might find similar markets to antivirus software.

Alternatively, there are strategies which avoid the core difficulty:

- You can potentially get the technology integrated into platforms that people already use


  - This might mean e.g. social media (cf. community notes on X), other content providers, or web browsers


- Or you can have the technology used by other systems or by experts, who help others to track what’s true (cf. how weather satellites help many people to have more accurate weather forecasts, even though almost nobody accesses data from the satellites directly)


  - To support this, you might build products targeted at experts

  - Or you might provide access to your technology via API, to make it easier for further products to integrate its benefits


### What’s at stake

These applications could make it easier for many people to track what’s true, who’s worth listening to, etc. But we envision most of the impact of the technology to arise not from helping individuals to navigate the informational landscapes in front of them, but by changing the shape of those informational landscapes in the first place. If people know that lies and obfuscation will make them look bad, they will be disincentivized from lying or obfuscating in the first place.

A community or society can occupy different equilibria in terms of how it relates to truth (analogous to different equilibria in terms of how it relates to corruption). To simplify:

- In a high-honesty equilibrium, most information shared publicly (at least by non-anonymous actors) is essentially honest. Deviations from this are frowned upon, and often worth someone’s time to dig into when they are suspected. As a result, honesty is generally the best policy. This means that most information is reliable, and so most people can keep a pretty clear sense of what’s happening.

- In a low-honesty equilibrium, there are only weak expectations of honesty. People lie a lot; it’s unremarkable, so they aren’t seriously penalized for it. As a result, lying is often incentivized. It’s hard for most people to know what to trust, so many people’s pictures of the world are pretty distorted.


Overall, high-honesty equilibria seem more desirable (just as low-corruption equilibria seem more desirable). This may feel intuitively obvious to many readers. From the perspective of avoiding existential risk, we think that high-honesty societies look better because:

- It’s harder for unscrupulous actors to concentrate power by manipulating the views of others;

- It’s easier for society to track which actions are incurring catastrophe risk, and correct [misaligned incentives](https://pmc.ncbi.nlm.nih.gov/articles/PMC5222861/) or avoid accidentally “sleepwalking off a cliff”;

- More broadly, having epistemically healthy environments seems robustly good for putting us in a better position, even if we can’t name all of the ways that might happen.


When technology changes the way that we interact, that represents a moment of flux, where it’s unusually up-in-the-air what equilibrium we will come down in. Especially if technology might change the payoffs for different actors (how expensive it is to monitor honesty, etc.), that could potentially reverberate, and have big effects on where we end up.

Deploying the right tools here (in a timely fashion) could help to nudge the equilibria of the future towards the high-honesty end of the spectrum. This is potentially relevant for both the relatively near-term (when people are using AI tools to help them navigate information environments not so different from those of today), as well as further out (when most knowledge artefacts may be AI-generated, and we may be mostly concerned with AI systems navigating information environments on our behalf).

_This article has gone through several rounds of development, and we experimented with getting AI assistance at various points in the preparation of this piece. We would like to thank Andreas Stuhmüller, Max Dalton, Raymond Douglas, Owain Evans, Lukas Finnveden, Julian Michael, Oliver Habryka, Ben Goldhaber, Will MacAskill, Isabel Juniewicz, Deger Turan, Jonas Vollmer, Max Daniel, Fin Moorhouse, Hilary Greaves, Stefan Torges, Kathleen Finlinson, Ozzie Gooen, and Anthony Aguirre for their input; and to apologise to anyone we've forgotten._

[**Design sketches for a more sensible world**](https://www.forethought.org/research/design-sketches-for-a-more-sensible-world) Article Series

Part 1 of 2

We think that near-term AI systems could transform our ability to reason and coordinate, significantly improving our chances of safely navigating the transition to advanced AI systems. This sequence gives a series of design sketches for specific technologies that we think could help. We hope that these sketches make a more sensible world easier to envision, and inspire people to start building the relevant tech.

[Design Sketches: Angels-on-the-Shoulder](https://www.forethought.org/research/design-sketches-angels-on-the-shoulder)

1

We’re highlighting five particular technologies. Of course in reality some of these blur into each other, and they’re just examples in a high-dimensional possibility space opened up by AI, which likely includes even better options. But we hope by being concrete we can help more people to start seriously thinking about the possibilities. We’ll discuss some more of the cross-cutting issues at the end, after we look at the details of what these technologies might involve. [↩](https://www.forethought.org/research/design-sketches-collective-epistemics#user-content-fnref-1)

2

For inspiration, see [report scoring the predictive performance of the Big 3](https://arbresearch.com/files/big_three.pdf). [↩](https://www.forethought.org/research/design-sketches-collective-epistemics#user-content-fnref-2)

3

For further exploration of this potential technology, see the “ [full epistemic stack](https://www.lesswrong.com/posts/DMswzhPQqkqx2XAma/a-full-epistemic-stack-knowledge-commons-for-the-21st-1)”. [↩](https://www.forethought.org/research/design-sketches-collective-epistemics#user-content-fnref-3)